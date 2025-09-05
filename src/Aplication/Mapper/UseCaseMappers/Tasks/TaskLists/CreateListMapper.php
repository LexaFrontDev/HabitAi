<?php

namespace App\Aplication\Mapper\UseCaseMappers\Tasks\TaskLists;

use App\Aplication\Dto\TasksDto\ListTasks\TasksListCreate;
use App\Aplication\Dto\TasksDto\ListTasks\TasksListReq;

class CreateListMapper
{
    public function isReqToDto(int $userId, TasksListReq $dto): TasksListCreate
    {
        return new TasksListCreate(
            user_id: $userId,
            label: $dto->label,
            priority:  $dto->priority,
            list_type: $dto->list_type,
        );
    }
}
