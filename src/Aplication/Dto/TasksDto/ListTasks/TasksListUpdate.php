<?php

namespace App\Aplication\Dto\TasksDto\ListTasks;

final class TasksListUpdate
{
    public function __construct(
        public readonly int $list_id,
        public readonly int $user_id,
        public readonly string $label,
        public readonly int $priority,
        public readonly string $list_type,
    ) {
    }
}
