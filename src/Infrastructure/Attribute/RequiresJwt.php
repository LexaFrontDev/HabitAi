<?php

namespace App\Infrastructure\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class RequiresJwt
{
    public function __construct(public bool $useHeader = false)
    {
    }
}
