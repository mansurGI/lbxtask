<?php

namespace App\Storage;

interface StorageInterface
{
    public function save($content, $name);

    public function get($name);
}