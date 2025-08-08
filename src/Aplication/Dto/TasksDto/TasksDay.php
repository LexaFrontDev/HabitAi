<?php

namespace App\Aplication\Dto\TasksDto;

use phpDocumentor\Reflection\Types\Boolean;

class TasksDay
{


    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly bool $todo,
        public readonly ?string $description = null,
        public readonly TasksDateGet $timeData,
    ){}


}