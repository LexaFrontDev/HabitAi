<?php

namespace App\Aplication\Dto\ConditionsDto;

class OnConditionDto
{
    public function __construct(
        public readonly string $expr,
        public readonly string $type,
    ) {
    }
}
