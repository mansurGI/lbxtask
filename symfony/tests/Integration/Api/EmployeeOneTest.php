<?php

namespace App\Tests\Integration\Api;

use App\Tests\ApiTestCase;

class EmployeeOneTest extends ApiTestCase
{
    /**
     * @covers \App\Controller\Api\EmployeeController::one()
     * @return void
     */
    public function testOne(): void
    {
        $response = $this->request('GET', '/api/employee/1');

        $this->assertEquals(200, $response['code']);
        $this->assertEquals('serafina.bumgarner@exxonmobil.com', $response['content']['email']);
    }
}
