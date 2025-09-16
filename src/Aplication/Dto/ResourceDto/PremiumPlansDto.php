<?php

namespace App\Aplication\Dto\ResourceDto;

final class PremiumPlansDto
{
    /**
     * @param string[] $features
     */
    public function __construct(
        public readonly string $name,
        public readonly string $desc,
        public readonly string $price,
        public readonly array $features,
        public readonly bool $highlight,
    ) {
    }
}
