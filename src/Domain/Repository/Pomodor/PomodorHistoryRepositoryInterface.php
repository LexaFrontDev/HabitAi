<?php

namespace App\Domain\Repository\Pomodor;

use App\Aplication\Dto\PomodorDto\RepPomodorDto;
use App\Aplication\Dto\PomodorDto\RepPomodoroHistory;
use App\Domain\Entity\Pomdoro\PomodorHistory;

interface PomodorHistoryRepositoryInterface
{
    public function cretePomodorHistory(RepPomodorDto $pomodorDto): PomodorHistory;

    /**
     * @return RepPomodoroHistory[]
     */
    public function getHistoryByUserId(int $userId, int $limit = 50): array;

    public function deletePomodorHistory(int $pomodorId): bool;

    /**
     * @return PomodorHistory[]
     */
    public function getDataByUserIdAndPeriod(int $userId, int $target): array;

    /**
     * @return PomodorHistory[]
     */
    public function getPomoDayByUserId(int $userId): array;

    public function getAllCountPomodorByUserId(int $userId): int|bool;
}
