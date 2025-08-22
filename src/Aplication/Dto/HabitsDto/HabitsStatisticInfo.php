<?php

namespace App\Aplication\Dto\HabitsDto;

class HabitsStatisticInfo
{
    /**
     * @param HabitTrackingInfo[] $habit_tracking_info
     */
    public function __construct(
        public int $habit_id,
        public string $habit_name,
        public int $habit_all_tracking_quantity,
        public array $habit_tracking_info,
    ) {
    }
}
