<?php

namespace App\Domain\Repository\Habits;

use App\Aplication\Dto\HabitsDtoUseCase\GetHabitsProgress;
use App\Aplication\Dto\HabitsDtoUseCase\GetHabitsProgressHabitsTitle;
use App\Aplication\Dto\HabitsDtoUseCase\SaveHabitsProgress;

interface HabitsHistoryRepositoryInterface
{



    /**
        * Сохраняеть прогресс привычек или обновляеть в зависимости от id в @SaveHabitsProgress
        * @return int|bool
    */
    public function saveProgress(SaveHabitsProgress $saveHabitsProgress, int $countPurposes): int|false;



    public function getProgressToday($userId): array|false;


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
     * @param int $userId
     * @return int|bool
     */
    public function getCountDoneHabits(int $userId): int|bool;



}

