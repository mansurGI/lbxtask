<?php

namespace App\Tests\Integration\MessageHandler;

use App\Message\CsvUploaded;
use App\MessageHandler\CsvUploadedHandler;
use App\Service\CsvFileManager;
use App\Service\CsvImportService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CsvUploadedHandlerTest extends KernelTestCase
{
    /**
     * @covers \App\MessageHandler\CsvUploadedHandler::__invoke()
     */
    public function testInvoke(): void
    {
        $csvFileManagerMock = $this->createMock(CsvFileManager::class);
        $csvFileManagerMock->method('getPath')->willReturn('some-path');

        /** @var LoggerInterface $logger */
        $logger = static::getContainer()->get(LoggerInterface::class);

        $csvImportServiceMock = $this->createMock(CsvImportService::class);

        $message = new CsvUploaded('some-file');
        $handler = new CsvUploadedHandler($csvFileManagerMock, $logger, $csvImportServiceMock);

        $handler($message);
        $this->assertTrue(true);
    }

    /**
     * @covers \App\MessageHandler\CsvUploadedHandler::__invoke()
     */
    public function testInvokeWithException()
    {
        $csvFileManagerMock = $this->createMock(CsvFileManager::class);
        $csvFileManagerMock->method('getPath')->willReturn('some-path');

        /** @var LoggerInterface $logger */
        $logger = static::getContainer()->get(LoggerInterface::class);

        $csvImportServiceMock = $this->createMock(CsvImportService::class);
        $csvImportServiceMock->method('process')
            ->willThrowException(new \LogicException('Error on csv importing'));

        $message = new CsvUploaded('some-file');
        $handler = new CsvUploadedHandler($csvFileManagerMock, $logger, $csvImportServiceMock);

        $this->expectException(\LogicException::class);
        $handler($message);
    }

}