<?php

namespace App\Domain\Repository\Matric;

use App\Aplication\Dto\Matric\MatricWithTasks;
use App\Aplication\Dto\Matric\RenameColumnMatric;
use App\Aplication\Dto\Matric\ReplaceMatricColumn;

interface MatricInterface
{
    /**
     * @param int $userId
     * @param int $tasksId
     * @return MatricWithTasks
     * @return \http\Exception\RuntimeException
     */
    public function getMatricWithTasks(int $userId, int $tasksId): MatricWithTasks;



    /**
     * @param ReplaceMatricColumn $dto
     * @return bool
     */
    public function replaceMatric(ReplaceMatricColumn $dto): bool;

    /**
     * @param int $taskId
     * @param int $userId
     * @return bool
     */
    public function saveMatric(int $taskId, int $userId): bool;

    /**
     * @param int $taskId
     * @param int $userId
     * @return bool
     */
    public function deleteMatric(int $taskId, int $userId): bool;

}