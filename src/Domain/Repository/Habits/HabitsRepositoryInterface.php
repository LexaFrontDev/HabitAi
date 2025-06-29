<?php

namespace App\Domain\Repository\Habits;

use App\Aplication\Dto\HabitsDtoUseCase\ReqHabitsDto;
use App\Aplication\Dto\HabitsDtoUseCase\SaveHabitDto;

interface HabitsRepositoryInterface
{


    /**
     * @param int $userId
     * @return mixed
     */
    public function getByUserId(int $userId);

    /**
     * @param SaveHabitDto $reqHabitsDto
     * @return int|bool
     */

    public function saveHabits(SaveHabitDto $reqHabitsDto): int|bool;


    /**
     * Возвращает привычки пользователя, которые активны на сегодня.
     *
     * Использует три типа расписаний:
     * - Ежедневные (daily): проверяет, что сегодня активен день недели, например 'monday' => true
     * - Еженедельные (weekly): сравнивает номер дня недели (0 — воскресенье, 6 — суббота)
     * - Повторяющиеся в месяц (repeat): проверяет, совпадает ли день месяца
     *
     * Параметры:
     * @param int $day
     * @param int $week
     * @param int $month
     * @param int $userId
     * @return array
     */
    public function getHabitsForToday(int $day, int $week, int $month,  int $userId): array;


    /**
     * @param int $day
     * @param int $week
     * @param int $month
     * @param int $userId
     * @return int
     */
    public function getCountHabitsToday(int $day, int $week, int $month, int $userId): int;

    /**
     * @param int $habitId
     * @param int $userId
     * @param SaveHabitDto $dto
     * @return bool
     */
    public function updateHabitById(int $habitId, int $userId, SaveHabitDto $dto): bool;

    /**
     * @param int $habitId
     * @param int $userId
     * @return bool
     */
    public function deleteHabitById(int $habitId, int $userId): bool;
}