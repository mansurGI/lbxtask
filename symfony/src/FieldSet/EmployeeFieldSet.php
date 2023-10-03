<?php

namespace App\FieldSet;

class EmployeeFieldSet implements FieldSetInterface
{
    private static array $fieldSet = [
        0 => 'eid',
        1 => 'prefix',
        2 => 'firstname',
        3 => 'middleInitial',
        4 => 'lastname',
        5 => 'gender',
        6 => 'email',
        7 => 'birthDate',
        8 => 'birthTime',
        9 => 'age',
        10 => 'joinDate',
        11 => 'tenure',
        12 => 'phone',
        13 => 'place',
        14 => 'county',
        15 => 'city',
        16 => 'zipcode',
        17 => 'region',
        18 => 'username',
    ];

    public static function toArray(): array
    {
        return self::$fieldSet;
    }
}