<?php

namespace App\Serializer;

use App\Entity\Employee;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @method  getSupportedTypes(?string $format)
 */
class EmployeeSerializer implements DenormalizerInterface
{

    public function __construct(
        private readonly ObjectNormalizer $objectNormalizer,
    )
    {
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        $data['eid'] = (int)$data['eid'];

        $object = $this->objectNormalizer->denormalize($data, $type, $format, $context);
        
        return $object;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null)
    {
        return $type === Employee::class;
    }
}