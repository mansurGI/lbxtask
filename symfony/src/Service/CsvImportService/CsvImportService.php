<?php

namespace App\Service\CsvImportService;

use App\Connection\BatchConnection;
use App\CsvFieldsSet\EmployeeCsvFieldsSet;
use App\Entity\Employee;
use App\Service\CsvImportService\Exceptions\MaxDatabaseInsertErrorsReached;
use App\Service\CsvImportService\Exceptions\ParsingException;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CsvImportService
{
    const MAX_DATABASE_INSERT_ERRORS = 20;
    const BATCH_SIZE = 20;

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly BatchConnection $connection,
    )
    {
    }

    /**
     * @param resource $stream
     * @return void
     * @throws \Doctrine\DBAL\Exception
     * @throws ParsingException
     * @throws MaxDatabaseInsertErrorsReached
     */
    public function process($stream): void
    {
        try {
            $reader = Reader::createFromStream($stream);
            $reader->setHeaderOffset(0);
            $records = $reader->getRecords(EmployeeCsvFieldsSet::fromHeader($reader->getHeader()));
        } catch (\Throwable $exception) {
            $this->logger->error('Error on parsing csv', [
                'exception' => $exception,
            ]);
            throw new ParsingException('Error on parsing csv');
        }

        $inserted = 0;
        $insertErrors = 0;
        $employees = [];
        foreach ($records as $record) {
            try {
                /** @var Employee $employee */
                $employee = $this->serializer->denormalize($record, Employee::class, null, [
                    'disable_type_enforcement' => true,
                ]);
            } catch (\Throwable $exception) {
                continue;
            }

            $errors = $this->validator->validate($employee);
            if (count($errors) > 0) {
                continue;
            }

            if ($inserted === 0) {
                $this->connection->beginTransaction();
                $employees = [];
            }

            $employees[] = $this->serializer->normalize($employee);
            $inserted++;

            if ($inserted >= self::BATCH_SIZE) {
                $inserted = 0;
                try {
                    $this->connection->insertIgnore('employee', $employees);
                } catch (\Throwable $exception) {
                    $this->logger->error('Doctrine insert error', [
                        'exception' => $exception,
                    ]);

                    $this->connection->rollBack();

                    $insertErrors++;
                    if ($insertErrors >= self::MAX_DATABASE_INSERT_ERRORS) {
                        throw new MaxDatabaseInsertErrorsReached();
                    }

                    continue;
                }

                $this->connection->commit();
            }
        }

        if ($inserted !== 0) {
            try {
                $this->connection->insertIgnore('employee', $employees);
                $this->connection->commit();
            } catch (\Throwable $exception) {
                $this->logger->error('Doctrine insert error', [
                    'exception' => $exception,
                ]);

                $this->connection->rollBack();
            }
        }
    }
}