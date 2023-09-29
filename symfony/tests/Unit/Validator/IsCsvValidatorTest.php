<?php

namespace App\Tests\Unit\Validator;

use App\Validator\IsCsv;
use App\Validator\IsCsvValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class IsCsvValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): IsCsvValidator
    {
        return new IsCsvValidator();
    }

    /**
     * @dataProvider data()
     */
    public function test($value, $errors)
    {
        $this->validator->validate($value, new IsCsv());

        $this->assertEquals($errors, $this->context->getViolations()->count());
    }

    private function data(): array
    {
        return [
            [
                'value' => 'Id, Name, Phone' . PHP_EOL . '0, Darvin, 19231291' . PHP_EOL . '1, Steve, 788954223',
                'errors' => 0,
            ],
            [
                'value' => 'Id, Name, Phone' . PHP_EOL . '0, Darvin' . PHP_EOL . '1, Steve, 193828132',
                'errors' => 1,
            ],
            [
                'value' => 'Id, Name, Phone 0, Darvin, 1321312, 1, Steve, 123213123',
                'errors' => 1,
            ],
            [
                'value' => 'Id, Name' . PHP_EOL . PHP_EOL . PHP_EOL . 'Foo, Bar, Zoo',
                'errors' => 1,
            ],
            [
                'value' => 'dawdawd',
                'errors' => 1,
            ]
        ];
    }
}