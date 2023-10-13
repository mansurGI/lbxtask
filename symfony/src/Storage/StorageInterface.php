<?php

namespace App\Storage;

use App\Storage\Exceptions\NotFoundException;
use App\Storage\Exceptions\UnableToSaveException;

interface StorageInterface
{
    /**
     * @param resource $content
     * @param string $name
     * @return string
     * @throws UnableToSaveException
     */
    public function save($content, string $name): string;

    /**
     * @param string $name
     * @return resource
     * @throws NotFoundException
     */
    public function get(string $name);
}