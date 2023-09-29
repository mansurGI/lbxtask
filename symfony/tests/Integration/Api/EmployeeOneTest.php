<?php

namespace App\Tests\Integration\Api;

use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class EmployeeOneTest extends ApiTestCase
{
    /**
     * @covers \App\Controller\Api\EmployeeController::one()
     * @return void
     */
    public function testOne(): void
    {
        $response = $this->request('GET', '/api/employee/198429');

        $this->assertEquals(Response::HTTP_OK, $response['code']);
        $this->assertEquals('serafina.bumgarner@exxonmobil.com', $response['content']['email']);
    }
}
