<?php

namespace App\Domain\Repository\Habits;

use App\Aplication\Dto\HabitsDto\SaveHabitDto;
use App\Domain\Entity\Habits\Habit;
use Doctrine\DBAL\Exception;

interface HabitsRepositoryInterface
{
    public function getByUserId(int $userId): Habit;

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
     *
     * @return array<int, array<string, mixed>>|false
     *
     * @throws Exception
     */
    public function getHabitsForToday(int $day, int $week, int $month, int $userId): array|bool;

    public function getCountHabitsToday(int $day, int $week, int $month, int $userId): int;

    public function updateHabitById(int $habitId, int $userId, SaveHabitDto $dto): bool;

    public function deleteHabitById(int $habitId, int $userId): bool;
}
