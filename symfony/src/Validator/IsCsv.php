<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsCsv extends Constraint
{
    public string $message = 'The content is not in a csv format';

    public function __construct(string $message = null, array $groups = null, mixed $payload = null, mixed $options = null,)
    {
        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}