<?php

namespace App\Aplication\Dto\HabitsDto;

class HabitTrackingInfo
{
    public function __construct(
        public int $tracking_static_count,
        public int $tracking_dynamic_count,
        public string $tracking_create_data,
    ) {
    }
}
