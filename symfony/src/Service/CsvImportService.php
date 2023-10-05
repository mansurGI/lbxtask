<?php

namespace App\Service;

use App\Entity\Employee;
use App\FieldSet\EmployeeFieldSet;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CsvImportService
{

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $doctrine,
    )
    {
    }

    /**
     * @param string $path
     * @return void
     * @throws \Throwable
     */
    public function process(string $path): void
    {
        try {
            // reader create from resource
            $reader = Reader::createFromPath($path, 'r');
            $records = $reader->getRecords(EmployeeFieldSet::toArray());
            // $reader->getHeader();
            // field list should be built on a getHeader
            // to secure column's position change
        } catch (\Throwable $exception) {
            $this->logger->error('Unable to get csv', [
                'exception' => $exception,
            ]);
            // throw service exception - unable to get file
            // src/Service/CsvImportService/_ = exceptions and service class
            // cz our project has only one goal/one business task we can store exceptions in src/Exceptions
            throw $exception;
        }

        $count = 0;
        foreach ($records as $record) {
            /** @var Employee $employee */
            $employee = $this->serializer->deserialize($record, Employee::class, null, [
                'disable_type_enforcement' => true,
            ]);

            $errors = $this->validator->validate($employee);
            if (count($errors) > 0) {
                // log row number and eid (to search in csv what is wrong)
                $this->logger->error('validation err', ['err' => $errors]);
                continue;
            }



//            if (null !== $this->doctrine->getRepository(Employee::class)->findOneBy(['eid' => $employee->getEid()])) {
//                continue;
//            }
            // insert ignore
            // insert if not exists

            // pdo, doctrine->getConnection()

            // doctrine
            // begin transaction
            // raw sql insert ignore; setParameter() :val ?val (array keys/value/map)
            // commit transaction

            // insert 20 within 1 transaction
            // if fails insert one by one
            // if fails log and skip failed employee

            // error count restriction = 20
            // if reached - log and throw exception. we say no to your file
            // so we have предсказуемые затраты по времени на файл

            // ! broken import batch as message to transport (send batch as row numbers with broken employee)

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