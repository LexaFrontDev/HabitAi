<?php

namespace App\Aplication\Mapper\UseCaseMappers\Tasks\TaskLists;

use App\Aplication\Dto\TasksDto\ListTasks\TasksListReqWithListId;
use App\Aplication\Dto\TasksDto\ListTasks\TasksListUpdate;

class UpdateListMapper
{
    public function isReqToDto(int $userId, TasksListReqWithListId $dto): TasksListUpdate
    {
        return new TasksListUpdate(
            list_id: $dto->tasks_list_id,
            user_id: $userId,
            label: $dto->label,
            priority:  $dto->priority,
            list_type: $dto->list_type,
        );
    }
}
