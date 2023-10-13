<?php

namespace App\Tests\Integration\Api\Employee;

use App\Service\CsvFileManager;
use App\Tests\ApiTestCase;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;

class EmployeeImportTest extends ApiTestCase
{
    /**
     * @covers \App\Controller\Api\EmployeeController::import()
     */
    public function testImportSuccessful()
    {
        $csvFileManagerMock = $this->createMock(CsvFileManager::class);
        $csvFileManagerMock->method('upload')->willReturn('uploaded_file.csv');

        $response = $this->request(
            'POST', '/api/employee',
            'Id, Name, Age' . PHP_EOL . '0, David, 20' . PHP_EOL . '1, Steve, 19',
            [
                CsvFileManager::class => $csvFileManagerMock,
            ]
        );

        /** @var InMemoryTransport $transport */
        $transport = $this->getContainer()->get('messenger.transport.async');
        $this->assertCount(1, $transport->getSent());

        $this->assertEquals(Response::HTTP_OK, $response['code']);
        $this->assertEquals(['status' => 'done'], $response['content']);
    }

    /**
     * @covers \App\Controller\Api\EmployeeController::import()
     */
    public function testImportFailed()
    {
        $csvFileManagerMock = $this->createMock(CsvFileManager::class);
        $csvFileManagerMock->method('upload')->willThrowException(
            new IOException('Unable to create a file')
        );

        $response = $this->request(
            'POST', '/api/employee',
            'Id, Name, Age' . PHP_EOL . '0, David, 20' . PHP_EOL . '1, Steve, 19',
            [
                CsvFileManager::class => $csvFileManagerMock,
            ]
        );

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response['code']);
        $this->assertEquals(['status' => 'server error'], $response['content']);
    }

}