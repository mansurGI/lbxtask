<?php

namespace App\Tests\Integration\Api\Employee;

use App\Service\CsvFileManager;
use App\Tests\ApiTestCase;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBus;

class EmployeeImportTest extends ApiTestCase
{
    /**
     * @covers \App\Controller\Api\EmployeeController::import()
     * @dataProvider data()
     */
    public function testImport($payload, $result)
    {
        $response = $this->request('POST', '/api/employee', $payload);

        $this->assertEquals($result['code'], $response['code']);
        $this->assertEquals($result['hasErrors'], isset($response['content']['errors']));
    }

    private function data(): array
    {
        return [
            [
                'payload' => null,
                'result' => ['code' => Response::HTTP_BAD_REQUEST, 'hasErrors' => true],
            ],
            [
                'payload' => '',
                'result' => ['code' => Response::HTTP_BAD_REQUEST, 'hasErrors' => true],
            ],
            [
                'payload' => 'Id, Name, Age' . '0, David, 20' . '1, Steve, 19',
                'result' => ['code' => Response::HTTP_BAD_REQUEST, 'hasErrors' => true],
            ],
        ];
    }

    /**
     * @covers \App\Controller\Api\EmployeeController::import()
     */
    public function testImportMocked()
    {
        $csvFileManagerMock = $this->createMock(CsvFileManager::class);
        $csvFileManagerMock->method('upload')->willReturn('uploaded_file.csv');

        $messengerMock = $this->createMock(MessageBus::class);
        $messengerMock->method('dispatch')->willReturn(new Envelope(new \stdClass()));

        $response = $this->request(
            'POST', '/api/employee',
            'Id, Name, Age' . PHP_EOL . '0, David, 20' . PHP_EOL . '1, Steve, 19',
            [
                CsvFileManager::class => $csvFileManagerMock,
                MessageBus::class => $messengerMock,
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $response['code']);
        $this->assertEquals(['status' => 'done'], $response['content']);
    }

    // Almost a duplicate of a previous test bcz of:
    // TODO: in order to have a multiple request with different mocks we have to shut Kernel down,
    // TODO: boot it and add mock for every request
    /**
     * @covers \App\Controller\Api\EmployeeController::import()
     */
    public function testImportMocked2()
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