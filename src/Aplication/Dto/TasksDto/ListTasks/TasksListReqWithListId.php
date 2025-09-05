<?php

namespace App\Aplication\Dto\TasksDto\ListTasks;

final class TasksListReqWithListId
{
    public function __construct(
        public readonly int $tasks_list_id,
        public readonly string $label,
        public readonly int $priority,
        public readonly string $list_type,
    ) {
    }

}
