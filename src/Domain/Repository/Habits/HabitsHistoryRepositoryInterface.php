<?php

namespace App\Domain\Repository\Habits;

use App\Aplication\Dto\HabitsDto\GetHabitsProgress;
use App\Aplication\Dto\HabitsDto\GetHabitsProgressHabitsTitle;
use App\Aplication\Dto\HabitsDto\SaveHabitsProgress;

interface HabitsHistoryRepositoryInterface
{
    /**
     * Сохраняеть прогресс привычек или обновляеть в зависимости от id в @SaveHabitsProgress
     */
    public function saveProgress(SaveHabitsProgress $saveHabitsProgress, int $countPurposes): int|false;

    /**
     * Возвращает прогресс пользователя на сегодня
     *
     * @return array<int, GetHabitsProgress>|false
     */
    public function getProgressToday(int $userId): array|false;

    /**
     * Возвращает весь прогресс для статистики
     *
     * @return GetHabitsProgress[]|false
     */
    public function getAllProgress(int $userId): array|false;

    /**
     * Возвращает весь прогресс с названием привычек для статистики
     *
     * @return GetHabitsProgressHabitsTitle[]|false
     */
    public function getAllProgressWithHabitsTitle(int $userId): array|false;

    /**
     * Получает количество выполненных задач
     */
    public function getCountDoneHabits(int $userId): int|bool;
}
