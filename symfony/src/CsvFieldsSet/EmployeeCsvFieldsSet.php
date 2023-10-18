<?php

namespace App\CsvFieldsSet;

class EmployeeCsvFieldsSet implements CsvFieldsSetInterface
{
    private static array $fields = [
        'eid' => ['emp id', 'employee id', 'emp'],
        'prefix' => ['prefix', 'name prefix'],
        'firstname' => ['firstname', 'first name', 'name'],
        'middleInitial' => ['middle initial', 'middle character'],
        'lastname' => ['last name', 'surname'],
        'gender' => ['gender', 'sex'],
        'email' => ['email', 'e mail', 'e-mail', 'email address'],
        'birthDate' => ['birth date', 'date of birth', 'birthday'],
        'birthTime' => ['birth time', 'time of birth'],
        'age' => ['age', 'age in yrs.'],
        'joinDate' => ['join date', 'date hired', 'date of joining'],
        'tenure' => ['tenure', 'age in company (years)'],
        'phone' => ['phone', 'phone no.', 'mobile'],
        'place' => ['place', 'place name'],
        'county' => ['county'],
        'city' => ['city'],
        'zipcode' => ['zipcode', 'postalcode', 'zip'],
        'region' => ['region'],
        'username' => ['username', 'user name'],
    ];

    public static function fromHeader(array $header): array
    {
        $fieldSet = [];

        foreach ($header as $columnPosition => $columnName) {
            $fieldSet[$columnPosition] = (string) $columnPosition;

            foreach (self::$fields as $fieldName => $fieldNameAliases) {
                if (in_array(mb_strtolower(trim($columnName)), $fieldNameAliases)) {
                    if (false === in_array($fieldName, $fieldSet)) {
                        $fieldSet[$columnPosition] = $fieldName;
                    }
                }
            }
        }

        return $fieldSet;
    }
}