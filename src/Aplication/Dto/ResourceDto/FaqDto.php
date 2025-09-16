<?php

namespace App\Aplication\Dto\ResourceDto;

final class FaqDto
{
    public function __construct(
        public readonly string $question,
        public readonly string $answer,
    ) {
    }
}
