<?php

namespace App\Aplication\Dto\HabitsDto;

class HabitsMonthStatistic
{
    public function __construct(
        /** @var HabitsDayInfo[] $days */
        public readonly array $days,
    ) {
    }
}
