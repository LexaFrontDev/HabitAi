<?php

namespace App\Aplication\Dto\TasksDto\ListTasks;

final class TasksListReq
{
    public function __construct(
        public readonly string $label,
        public readonly int $priority,
        public readonly string $list_type,
    ) {
    }
}
