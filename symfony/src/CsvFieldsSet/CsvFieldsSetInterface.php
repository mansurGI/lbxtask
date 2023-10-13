<?php

namespace App\CsvFieldsSet;

interface CsvFieldsSetInterface
{
    public static function fromHeader(array $header): array;
}