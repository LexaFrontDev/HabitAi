<?php

namespace App\Domain\Repository\Tasks;

use App\Aplication\Dto\TasksDto\ListTasks\TasksListCreate;
use App\Aplication\Dto\TasksDto\ListTasks\TasksListUpdate;

interface ListTasksInterface
{
    public function saveListTask(TasksListCreate $task): bool;

    public function updateListTask(TasksListUpdate $task): bool;
}
