<?php

namespace App\Aplication\Dto\ResourceDto;

final class LanguageDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $prefix,
    ) {
    }
}
