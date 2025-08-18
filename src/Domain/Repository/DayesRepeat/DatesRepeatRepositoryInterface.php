<?php

namespace App\Domain\Repository\DayesRepeat;

use App\Domain\Exception\NotFoundException\NotFoundException;

interface DatesRepeatRepositoryInterface
{
    public function saveDatesRepeat(int $day): int|bool;

    public function updateDatesRepeat(int $id, int $day): bool;

    /**
     * @throws NotFoundException
     */
    public function deleteDatesRepeat(int $id): bool;
}
