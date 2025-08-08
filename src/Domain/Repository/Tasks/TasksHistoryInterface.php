<?php


declare(strict_types = 1);

namespace App\Domain\Repository\Tasks;

interface TasksHistoryInterface
{

    /**
     * @param int $userID
     * @param int $tasksId
     * @param string|null $monthly
     * @param string|null $date
     * @return int|bool
     */
    public function tasksToDoSave(int $userID, int $tasksId, string $monthly = null, string $date = null): int|bool;


    /**
     * @param int $userID
     * @param int $tasksId
     * @return bool
     */
    public function tasksWontDo(int $userID, int $tasksId): bool;
}