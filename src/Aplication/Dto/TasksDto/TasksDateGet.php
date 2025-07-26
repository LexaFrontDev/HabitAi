<?php

namespace App\Aplication\Dto\TasksDto;

class TasksDateGet
{


    public function __construct(
        public readonly ?\DateTime $date = null,
        public readonly ?string $time = null,
        public readonly ?string $repeat = null,
        public readonly TasksDurationGet $duration,
    ){}
}