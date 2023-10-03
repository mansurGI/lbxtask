<?php

namespace App\Message;

class CsvUploaded
{
    public function __construct(
        private readonly string $fileName,
    )
    {
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

}