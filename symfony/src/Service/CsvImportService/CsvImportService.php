<?php

namespace App\Service\CsvImportService;

use App\CsvFieldsSet\EmployeeCsvFieldsSet;
use App\Entity\Employee;
use App\Service\CsvImportService\Exceptions\MaxDatabaseInsertErrorsReached;
use App\Service\CsvImportService\Exceptions\ParsingException;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CsvImportService
{
    const MAX_DATABASE_INSERT_ERRORS = 20;

    private int $insertErrors = 0;

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $doctrine,
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
        foreach ($records as $record) {
            /** @var Employee $employee */
            $employee = $this->serializer->deserialize($record, Employee::class, null);

            $errors = $this->validator->validate($employee);
            if (count($errors) > 0) {
                continue;
            }

            if ($inserted === 0) {
                $this->doctrine->getConnection()->beginTransaction();
            }

            try {
                $this->doctrine->getRepository(Employee::class)->insertIgnore($this->serializer->normalize($employee));
            } catch (\Throwable $exception) {
                $this->logger->error('Doctrine insert error', [
                    'exception' => $exception,
                ]);
                $this->insertErrors++;
                if ($this->insertErrors >= self::MAX_DATABASE_INSERT_ERRORS) {
                    throw new MaxDatabaseInsertErrorsReached();
                }
                continue;
            }
            $inserted++;

            if ($inserted >= 20) {
                $inserted = 0;
                $this->doctrine->getConnection()->commit();
                $this->doctrine->clear();
            }
        }

        if ($inserted !== 0) {
            $this->doctrine->getConnection()->commit();
            $this->doctrine->clear();
        }
    }
}