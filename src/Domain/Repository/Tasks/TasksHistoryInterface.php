<?php


declare(strict_types=1);

namespace App\Domain\Repository\Tasks;

interface TasksHistoryInterface
{
    public function tasksToDoSave(int $userID, int $tasksId, ?string $monthly = null, ?string $date = null): int|bool;

    public function tasksWontDo(int $userID, int $tasksId): bool;
}
