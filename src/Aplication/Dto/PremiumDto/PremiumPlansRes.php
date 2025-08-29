<?php

namespace App\Aplication\Dto\PremiumDto;

final class PremiumPlansRes
{
    /**
     * @param string[] $features
     */
    public function __construct(
        public readonly string $name,
        public readonly string $desc,
        public readonly array $features,
        public readonly bool $highlight,
    ) {
    }

}
