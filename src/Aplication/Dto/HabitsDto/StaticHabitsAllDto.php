<?php

namespace App\Aplication\Dto\HabitsDto;

class StaticHabitsAllDto
{
    /**
     * @param GetHabitsProgressHabitsTitle[] $habitsList
     */
    public function __construct(
        public readonly array $habitsList,
        public readonly int $all_tracking_days,
        public readonly int $all_tracking_count,
        public readonly int $tracking_today,
        public readonly int $tracking_week,
    ) {
    }


}
