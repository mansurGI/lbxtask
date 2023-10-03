<?php

namespace App\MessageHandler;

use App\Entity\Employee;
use App\FieldSet\EmployeeFieldSet;
use App\Message\CsvUploaded;
use App\Service\CsvFileManager;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsMessageHandler]
class CsvUploadedHandler
{
    public function __construct(
        private readonly CsvFileManager         $csvFileManager,
        private readonly ObjectNormalizer       $normalizer,
        private readonly ValidatorInterface     $validator,
        private readonly EntityManagerInterface $doctrine,
        private readonly LoggerInterface        $logger,
    )
    {
    }

    public function __invoke(CsvUploaded $csvUploaded): void
    {
        $path = $this->csvFileManager->getPath($csvUploaded->getFileName());

        try {
            $reader = Reader::createFromPath($path, 'r');
            $records = $reader->getRecords(EmployeeFieldSet::toArray());
        } catch (\Throwable $exception) {
            $this->logger->error('Unable to get csv', [
                'exception' => $exception,
            ]);
            return;
        }

        $count = 0;
        foreach ($records as $record) {
            /** @var Employee $employee */
            $employee = $this->normalizer->denormalize($record, Employee::class);

            $errors = $this->validator->validate($employee);
            if (count($errors) > 0) {
                continue;
            }

//            if (null !== $this->doctrine->getRepository(Employee::class)->findOneBy(['eid' => $employee->getEid()])) {
//                continue;
//            }

            $this->doctrine->persist($employee);
            $count++;

            if ($count >= 20) {
                $this->doctrine->flush();
                $this->doctrine->clear();
                $count = 0;
            }
        }

        if ($count !== 0) {
            $this->doctrine->flush();
            $this->doctrine->clear();
        }
    }
}