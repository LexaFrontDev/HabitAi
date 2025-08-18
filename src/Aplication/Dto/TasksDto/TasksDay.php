<?php

namespace App\Aplication\Dto\TasksDto;

class TasksDay
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly bool $todo,
        public readonly TasksDateGet $timeData,
        public readonly ?string $description = null,
    ) {
    }


}
