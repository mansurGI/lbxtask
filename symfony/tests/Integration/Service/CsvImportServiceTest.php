<?php

namespace App\Tests\Integration\Service;

use App\Service\CsvImportService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CsvImportServiceTest extends KernelTestCase
{
    /**
     * @covers \App\Service\CsvImportService::process()
     */
    public function testProcess():void
    {
        /** @var LoggerInterface $logger */
        $logger = static::getContainer()->get(LoggerInterface::class);

        /** @var SerializerInterface $serializer */
        $serializer = static::getContainer()->get(SerializerInterface::class);

        /** @var ValidatorInterface $validator */
        $validator = static::getContainer()->get(ValidatorInterface::class);

        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get(EntityManagerInterface::class);

        $csvImportService = new CsvImportService($logger, $serializer, $validator, $em);

        $csvImportService->process('somePath');
    }
}