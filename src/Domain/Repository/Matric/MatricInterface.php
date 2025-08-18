<?php

namespace App\Domain\Repository\Matric;

use App\Aplication\Dto\Matric\MatricWithTasks;
use App\Aplication\Dto\Matric\ReplaceMatricColumn;

interface MatricInterface
{
    public function getMatricWithTasks(int $userId, int $tasksId): MatricWithTasks|bool;

    public function replaceMatric(ReplaceMatricColumn $dto): bool;

    public function saveMatric(int $taskId, int $userId): bool;

    public function deleteMatric(int $taskId, int $userId): bool;
}
