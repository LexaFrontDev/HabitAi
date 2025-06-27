<?php

namespace App\Domain\Repository\Habits;

use App\Aplication\Dto\HabitsDtoUseCase\ReqHabitsDto;
use App\Aplication\Dto\HabitsDtoUseCase\SaveHabitDto;

interface HabitsRepositoryInterface
{

    public function getByUserId(int $userId);
    public function saveHabits(SaveHabitDto $reqHabitsDto): int|bool;

    public function getHabitsForToday(int $day, int $week, int $month,  int $userId): array;

}