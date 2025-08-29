<?php

namespace App\Aplication\Dto\TasksDto\Tasks;

class TaskDurationDto
{
    public function __construct(
        public readonly ?string $startDate = null,
        public readonly ?string $startTime = null,
        public readonly ?string $endDate = null,
        public readonly ?string $endTime = null,
    ) {
    }

}
