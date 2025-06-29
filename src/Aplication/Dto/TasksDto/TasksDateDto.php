<?php

namespace App\Aplication\Dto\TasksDto;

class TasksDateDto
{
    public function __construct(
        public readonly ?string $date = null,
        public readonly string $time,
        public readonly string $repeat,
        public readonly TaskDurationDto $duration,
    ) {}
}