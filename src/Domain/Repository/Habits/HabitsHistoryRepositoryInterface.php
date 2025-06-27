<?php

namespace App\Domain\Repository\Habits;

use App\Aplication\Dto\HabitsDtoUseCase\GetHabitsProgress;
use App\Aplication\Dto\HabitsDtoUseCase\SaveHabitsProgress;

interface HabitsHistoryRepositoryInterface
{



    /**
        * Сохраняеть прогресс привычек или обновляеть в зависимости от id в @SaveHabitsProgress
        * @return int|bool
    */
    public function saveProgress(SaveHabitsProgress $saveHabitsProgress): int|false;



    public function getProgressToday($userId): array|false;


    /**
     * Возвращает весь прогресс для статистики
     *
     * @return GetHabitsProgress[]|false
     */
    public function getAllProgress(int $userId): array|false;

}

