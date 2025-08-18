<?php

namespace App\Aplication\Dto\TasksDto;

class TasksForUpdateDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly TasksDateDto $timeData,
        public readonly ?string $description = null,
    ) {
    }


}
