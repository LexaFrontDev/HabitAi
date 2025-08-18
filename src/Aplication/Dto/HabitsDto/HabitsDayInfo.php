<?php

namespace App\Aplication\Dto\HabitsDto;

class HabitsDayInfo
{
    public function __construct(
        public readonly int $total,
    ) {
    }
}
