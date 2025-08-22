<?php

namespace App\Aplication\Dto\HabitsDto;

class HabitsDayInfo
{
    public function __construct(
        public int $day,
        public readonly int $total_tracking,
    ) {
    }
}
