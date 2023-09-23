<?php

namespace App\Tests\Integration\Api;

use App\Tests\ApiTestCase;

class EmployeeListTest extends ApiTestCase
{
    /**
     * @covers \App\Controller\Api\EmployeeController::list()
     * @return void
     */
    public function testList(): void
    {
        $response = $this->request('GET', '/api/employee/list');

        $this->assertEquals(200, $response['code']);
        $this->assertCount(2, $response['content']);
        $this->assertEquals('serafina.bumgarner@exxonmobil.com', $response['content'][0]['email']);
    }
}
