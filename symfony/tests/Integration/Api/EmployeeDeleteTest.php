<?php

namespace App\Tests\Integration\Api;

use App\Entity\Employee;
use App\Tests\ApiTestCase;

class EmployeeDeleteTest extends ApiTestCase
{
    /**
     * @covers \App\Controller\Api\EmployeeController::delete()
     * @return void
     */
    public function testDelete(): void
    {
        $response = $this->request('DELETE', '/api/employee/1');

        $this->assertEquals(204, $response['code']);
        $this->assertEmpty($response['content']);

        /** @var Employee $employee */
        $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(['uid' => 1]);

        $this->assertEquals(Employee::STATUS_DELETED, $employee->getStatus());
    }
}
