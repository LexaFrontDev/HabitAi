<?php

namespace App\Aplication\Dto\Matric;

use App\Aplication\Dto\TasksDto\TasksDay;

class MatricWithTasks
{
    /**
     * @param TasksDay[] $tasks
     */
    public function __construct(
        public readonly int $tasksNumber,
        public readonly array $tasks,
    ) {
    }
}
