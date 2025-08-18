<?php

namespace App\Aplication\Dto\HabitsDto;

class HabitsYearStatistic
{
    public function __construct(
        public readonly string $monthLabel,
        /** @var HabitsMonthStatistic[] $monthInfo */
        public readonly array $monthInfo,
    ) {
    }
}
