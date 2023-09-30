<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsCsvValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (false === $constraint instanceof IsCsv) {
            throw new UnexpectedTypeException($constraint, IsCsv::class);
        }

        if (false === is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!preg_match('/(?:\s*(?:\"([^\"]*)\"|([^,]+))\s*,?)+?/', $value)) {
            $this->violate($constraint->message);
        }

        if (false === mb_strpos($value, PHP_EOL)) {
            $this->violate($constraint->message);
        }
        $header = mb_substr($value, 0, mb_strpos($value, PHP_EOL));

        if (false === mb_strpos($value, PHP_EOL, mb_strpos($value, PHP_EOL) + 1)) {
            $this->violate($constraint->message);
        }
        $row = mb_substr(
            $value,
            mb_strpos($value, PHP_EOL),
            mb_strpos($value, PHP_EOL, mb_strpos($value, PHP_EOL) + 1) - mb_strpos($value, PHP_EOL)
        );

        if (mb_substr_count($header, ',') !== mb_substr_count($row, ',')) {
            $this->violate($constraint->message);
        }
    }

    private function violate($message)
    {
        if ($this->context->getViolations()->count() > 0) {
            return;
        }

        $this->context->buildViolation($message)->addViolation();
    }
}