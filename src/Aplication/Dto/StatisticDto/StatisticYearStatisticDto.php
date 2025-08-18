<?php

namespace App\Aplication\Dto\StatisticDto;

use App\Aplication\Dto\HabitsDto\HabitsYearStatistic;

class StatisticYearStatisticDto
{
    public function __construct(
        /** @var string Формат Y-m-d (например: 2025-01-01) */
        public readonly string $year,
        /** @var HabitsYearStatistic[] $habitsYearStatistic */
        public readonly array $habitsYearStatistic,
    ) {
    }


}
