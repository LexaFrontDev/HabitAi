<?php

namespace App\Infrastructure\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class RequiresJwt
{
    public function __construct(
        public bool $useHeader = false
    ) {}
}
