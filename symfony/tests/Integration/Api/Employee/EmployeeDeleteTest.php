<?php

namespace App\Tests\Integration\Api\Employee;

use App\Entity\Employee;
use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class EmployeeDeleteTest extends ApiTestCase
{
    /**
     * @covers \App\Controller\Api\EmployeeController::delete()
     * @return void
     */
    public function testDelete(): void
    {
        $response = $this->request('DELETE', '/api/employee/198429');

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response['code']);
        $this->assertEmpty($response['content']);

        /** @var Employee $employee */
        $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(['eid' => 198429]);

        $this->assertEquals(Employee::STATUS_DELETED, $employee->getStatus());

        $response = $this->request('DELETE', '/api/employee/7777777777777');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response['code']);
    }
}
