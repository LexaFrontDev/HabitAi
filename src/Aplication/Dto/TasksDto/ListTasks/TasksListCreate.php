<?php

namespace App\Aplication\Dto\TasksDto\ListTasks;

final class TasksListCreate
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $label,
        public readonly int $priority,
        public readonly string $list_type,
    ) {
    }


}
