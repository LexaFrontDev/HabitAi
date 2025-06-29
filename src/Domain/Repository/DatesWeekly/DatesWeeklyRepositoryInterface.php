<?php

namespace App\Domain\Repository\DatesWeekly;

use App\Domain\Exception\NotFoundException\NotFoundException;

interface DatesWeeklyRepositoryInterface
{


    /**
     * @param int $count
     * @return int|bool
     */
    public function saveDatesWeekly(int $count): int|bool;

    /**
     * @param int $id
     * @param int $count
     * @return bool
     */
    public function updateDatesWeekly(int $id, int $count): bool;


    /**
     * @param int $id
     * @return bool
     * @throws NotFoundException
     */
    public function deleteDatesWeekly(int $id): bool;
}