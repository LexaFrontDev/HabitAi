<?php

namespace App\Domain\Repository\DayesRepeat;

use App\Domain\Exception\NotFoundException\NotFoundException;

interface DatesRepeatRepositoryInterface
{


    /**
     * @param int $day
     * @return int
     */
    public function saveDatesRepeat(int $day): int;

    /**
     * @param int $id
     * @param int $day
     * @return bool
     */
    public function updateDatesRepeat(int $id, int $day): bool;

    /**
     * @param int $id
     * @return bool
     * @throws NotFoundException
     */
    public function deleteDatesRepeat(int $id): bool;
}