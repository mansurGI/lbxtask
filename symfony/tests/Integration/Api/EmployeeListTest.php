<?php

namespace App\Tests\Integration\Api;

use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class EmployeeListTest extends ApiTestCase
{
    /**
     * @covers \App\Controller\Api\EmployeeController::list()
     * @return void
     */
    public function testList(): void
    {
        $response = $this->request('GET', '/api/employee');

        $this->assertEquals(Response::HTTP_OK, $response['code']);
        $this->assertCount(2, $response['content']);
        $this->assertEquals('serafina.bumgarner@exxonmobil.com', $response['content'][1]['email']);
    }
}
