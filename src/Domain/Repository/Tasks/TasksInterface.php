<?php

namespace App\Domain\Repository\Tasks;

use App\Aplication\Dto\TasksDto\TasksDay;
use App\Aplication\Dto\TasksDto\TasksForSaveDto;
use App\Aplication\Dto\TasksDto\TasksForUpdateDto;

interface TasksInterface
{
    public function tasksSave(int $userId, TasksForSaveDto $tasksForSaveDto): int|bool;

    public function updateTasks(int $userId, TasksForUpdateDto $tasksForUpdateDto): int|bool;

    public function deleteTasks(int $userId, int $id): bool;

    /**
     * Возвращает массив DTO задач пользователя за указанный день
     *
     * @param int $userId ID пользователя
     * @param int $day День в формате Ymd или timestamp
     *
     * @return TasksDay[] Массив задач за день
     */
    public function getTasksByDay(int $userId, int $day): array;

    /**
     * @return TasksDay[]
     */
    public function getTasksAllByUserId(int $userId): array;
}
