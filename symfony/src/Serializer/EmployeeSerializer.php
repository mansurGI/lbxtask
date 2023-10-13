<?php

namespace App\Serializer;

use App\Entity\Employee;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EmployeeSerializer implements DenormalizerInterface
{

    public function __construct(
        private readonly ObjectNormalizer $objectNormalizer,
    )
    {
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        $data['eid'] = (int)$data['eid'];

        $object = $this->objectNormalizer->denormalize($data, $type, $format, $context);

        return $object;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return $type === Employee::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Employee::class];
    }
}