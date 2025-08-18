<?php

namespace App\Infrastructure\Serializer\CmelCaseConverter;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class FlexibleCamelCaseToSnakeConverter implements NameConverterInterface
{
    public function normalize(string $propertyName): string
    {
        return strtolower(preg_replace('/[A-Z]/', '_$0', $propertyName));
    }

    public function denormalize(string $propertyName): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $propertyName))));
    }
}
