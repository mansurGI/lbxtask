<?php

namespace App\Message;

class CsvUploadedMessage
{
    public function __construct(
        private readonly string $filename,
    )
    {
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

}