<?php

namespace App\Aplication\Dto\TasksDto\Tasks;

class TasksDateDto
{
    public function __construct(
        public readonly string $time,
        public readonly string $repeat,
        public readonly TaskDurationDto $duration,
        public readonly ?string $date = null,
    ) {
    }
}
