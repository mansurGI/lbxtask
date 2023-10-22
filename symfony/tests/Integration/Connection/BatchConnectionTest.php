<?php

namespace App\Tests\Integration\Connection;


use App\Connection\BatchConnection;
use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BatchConnectionTest extends KernelTestCase
{
    /**
     * @covers \App\Connection\BatchConnection::insertIgnore()
     */
    public function testInsertIgnore($data, $result)
    {
        /** @var EntityManagerInterface $doctrine */
        $doctrine = self::getContainer()->get(EntityManagerInterface::class);

        /** @var BatchConnection $connection */
        $connection = self::getContainer()->get(BatchConnection::class);

        $oneEmployee = [
            'eid' => 334422,
            'prefix' => 'Mrs.',
            'firstname' => 'i am one row insert ignore',
            'middleInitial' => 'I',
            'lastname' => 'Bumgarner',
            'gender' => 'F',
            'email' => 'serafina.bumgarner@exxonmobil.com',
            'birthDate' => '9/21/1982',
            'birthTime' => '01:53:14 AM',
            'age' => 34.87,
            'joinDate' => '2/1/2008',
            'tenure' => 9.49,
            'phone' => '212-376-9125',
            'place' => 'Clymer',
            'county' => 'Chautauqua',
            'city' => 'Clymer',
            'zipcode' => 14724,
            'region' => 'Northeast',
            'username' => 'sibumgarner'
        ];

        $connection->insertIgnore('employee', $oneEmployee);

        $this->assertEquals(
            $oneEmployee['firstname'],
            $doctrine->getRepository(Employee::class)->findOneBy(['eid' => $oneEmployee['eid']])->getFirstname()
        );
    }

    /**
     * @covers \App\Connection\BatchConnection::insertIgnore()
     */
    public function testInsertIgnoreMultipleRows()
    {

        /** @var EntityManagerInterface $doctrine */
        $doctrine = self::getContainer()->get(EntityManagerInterface::class);

        /** @var BatchConnection $connection */
        $connection = self::getContainer()->get(BatchConnection::class);

        $employees = [
            [
                'eid' => 127721,
                'prefix' => 'Mrs.',
                'firstname' => 'i am multiple insert ignore #1',
                'middleInitial' => 'I',
                'lastname' => 'Bumgarner',
                'gender' => 'F',
                'email' => 'serafina.bumgarner@exxonmobil.com',
                'birthDate' => '9/21/1982',
                'birthTime' => '01:53:14 AM',
                'age' => 34.87,
                'joinDate' => '2/1/2008',
                'tenure' => 9.49,
                'phone' => '212-376-9125',
                'place' => 'Clymer',
                'county' => 'Chautauqua',
                'city' => 'Clymer',
                'zipcode' => 14724,
                'region' => 'Northeast',
                'username' => 'sibumgarner'
            ],
            [
                'eid' => 128821,
                'prefix' => 'Mrs.',
                'firstname' => 'i am multiple insert ignore #2',
                'middleInitial' => 'I',
                'lastname' => 'Bumgarner',
                'gender' => 'F',
                'email' => 'serafina.bumgarner@exxonmobil.com',
                'birthDate' => '9/21/1982',
                'birthTime' => '01:53:14 AM',
                'age' => 34.87,
                'joinDate' => '2/1/2008',
                'tenure' => 9.49,
                'phone' => '212-376-9125',
                'place' => 'Clymer',
                'county' => 'Chautauqua',
                'city' => 'Clymer',
                'zipcode' => 14724,
                'region' => 'Northeast',
                'username' => 'sibumgarner'
            ]
        ];

        $connection->insertIgnore('employee', $employees);

        $this->assertEquals(
            $employees[0]['firstname'],
            $doctrine->getRepository(Employee::class)->findOneBy(['eid' => $employees[0]['eid']])->getFirstname()
        );
        $this->assertEquals(
            $employees[1]['firstname'],
            $doctrine->getRepository(Employee::class)->findOneBy(['eid' => $employees[1]['eid']])->getFirstname()
        );
    }

}