<?php

namespace App\Tests\Integration\Api;

use App\Service\CsvFileManager;
use App\Tests\ApiTestCase;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\Response;

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

    public function testImportMocked()
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

        $this->assertEquals(Response::HTTP_OK, $response['code']);
        $this->assertEquals(['status' => 'done'], $response['content']);
    }

    // Almost a duplicate of a previous test - don't want to spend time on handling kernel shutdown for mocking services
    public function testImportMocked2()
    {
        $csvFileManagerMock = $this->createMock(CsvFileManager::class);
        $csvFileManagerMock->method('upload')->willThrowException(
            new IOException('Unable to create a file'),
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