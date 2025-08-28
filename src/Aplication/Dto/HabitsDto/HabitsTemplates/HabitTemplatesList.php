<?php

namespace App\Aplication\Dto\HabitsDto\HabitsTemplates;

final class HabitTemplatesList
{
    public function __construct(
        public readonly string $title,
        public readonly string $quote,
        public readonly string $notification,
        public readonly string $datesType,
    ) {
    }
}
