<?php

namespace App\Aplication\Dto\TasksDto;

class TaskDurationDto
{
    public function __construct(
        public readonly string $startDate,
        public readonly string $startTime,
        public readonly string $endDate,
        public readonly string $endTime,
    ) {}

}