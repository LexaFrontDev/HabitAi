<?php

namespace App\Aplication\Dto\Matric;

use App\Aplication\Dto\TasksDto\TasksDay;

class MatricWithTasks
{

    public function __construct(
        public readonly int $tasksNumber,
        /*** @var TasksDay[] */
        public readonly array $tasks,
    ){}
}