<?php

namespace App\Aplication\Dto\TasksDto;

class TasksDateGet
{
    public function __construct(
        public readonly ?\DateTimeInterface $date,
        public readonly TasksDurationGet $duration,
        public readonly ?string $time = null,
        public readonly ?string $repeat = null,
    ) {
    }
}
