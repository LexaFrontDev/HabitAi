<?php

namespace App\Aplication\Dto\ResourceDto;

final class HabitsTemplatesDto
{
    public function __construct(
        public readonly string $title,
        public readonly string $quote,
        public readonly string $notification,
        public readonly string $datesType,
    ) {
    }
}
