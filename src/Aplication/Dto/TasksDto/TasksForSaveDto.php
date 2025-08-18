<?php

namespace App\Aplication\Dto\TasksDto;

class TasksForSaveDto
{
    public function __construct(
        public readonly string $title,
        public readonly TasksDateDto $timeData,
        public readonly ?string $description = null,
    ) {
    }


}
