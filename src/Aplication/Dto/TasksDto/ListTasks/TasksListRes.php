<?php

namespace App\Aplication\Dto\TasksDto\ListTasks;

final class TasksListRes
{
    public function __construct(
        public readonly string $label,
        public readonly string $priority,
        public readonly string $list_type,
    ) {
    }
}
