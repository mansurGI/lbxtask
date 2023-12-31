<?php

namespace App\Tests\Unit\CsvFieldsSet;

use App\CsvFieldsSet\EmployeeCsvFieldsSet;
use PHPUnit\Framework\TestCase;

class EmployeeCsvFieldsSetTest extends TestCase
{
    /**
     * @covers       \App\CsvFieldsSet\EmployeeCsvFieldsSet::fromHeader()
     * @dataProvider data
     */
    public function testFromHeader(array $header, array $result)
    {
        $this->assertEquals($result, EmployeeCsvFieldsSet::fromHeader($header));
    }

    private function data(): array
    {
        return [
            [
                'header' => [
                    'Emp ID',
                    'Name Prefix',
                    'First Name',
                    'Middle Initial',
                    'Last Name',
                    'Gender',
                    'E Mail',
                    'Date of Birth',
                    'Time of Birth',
                    'Age in Yrs.',
                    'Date of Joining',
                    'Age in Company (Years)',
                    'Phone No. ',
                    'Place Name',
                    'County',
                    'City',
                    'Zip',
                    'Region',
                    'User Name'
                ],
                'result' => [
                    'eid',
                    'prefix',
                    'firstname',
                    'middleInitial',
                    'lastname',
                    'gender',
                    'email',
                    'birthDate',
                    'birthTime',
                    'age',
                    'joinDate',
                    'tenure',
                    'phone',
                    'place',
                    'county',
                    'city',
                    'zipcode',
                    'region',
                    'username',
                ],
            ],
            [
                'header' => [
                    'Emp',
                    'Name Prefix',
                    'prefix',
                    'User NAME',
                    'NAME preFIx',
                ],
                'result' => [
                    'eid',
                    'prefix',
                    '2',
                    'username',
                    '4',
                ],
            ],
            [
                'header' => [
                    'Employee Id',
                    'dawdaw',
                    '0102312'
                ],
                'result' => [
                    'eid',
                    '1',
                    '2'
                ],
            ],
        ];
    }
}