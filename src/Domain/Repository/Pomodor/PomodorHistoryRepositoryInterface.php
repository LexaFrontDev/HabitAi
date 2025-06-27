<?php

namespace App\Domain\Repository\Pomodor;

use App\Aplication\Dto\PomodorDto\RepPomodorDto;
use App\Aplication\Dto\PomodorDto\RepPomodoroHistory;
use App\Aplication\Dto\PomodorDto\ResPomdorCountStatistic;
use App\Domain\Entity\Pomdoro\PomodorHistory;

interface PomodorHistoryRepositoryInterface
{

    public function cretePomodorHistory(RepPomodorDto $pomodorDto): PomodorHistory;

    public function getHistoryByUserId(int $userId, int $limit = 50): array;
    public function deletePomodorHistory(int $pomodorId): bool;

    public function getDataByUserIdAndPeriod(int $userId, int $target): array;


    public function getPomoDayByUserId(int $userId): array;

    public function getAllCountPomodorByUserId(int $userId): int|bool;


}


