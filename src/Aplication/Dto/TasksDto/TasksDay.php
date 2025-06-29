<?php

namespace App\Aplication\Dto\TasksDto;

class TasksDay
{


    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly ?string $description = null,
        public readonly ?\DateTime $date = null,
        public readonly ?string $time = null,
        public readonly ?string $startDate = null,
        public readonly ?string $startTime = null,
        public readonly ?string $endDate = null,
        public readonly ?string $endTime = null,
    ){}


}