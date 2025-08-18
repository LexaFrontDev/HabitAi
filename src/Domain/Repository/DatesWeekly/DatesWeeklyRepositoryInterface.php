<?php

namespace App\Domain\Repository\DatesWeekly;

use App\Domain\Exception\NotFoundException\NotFoundException;

interface DatesWeeklyRepositoryInterface
{
    public function saveDatesWeekly(int $count): int|bool;

    public function updateDatesWeekly(int $id, int $count): bool;

    /**
     * @throws NotFoundException
     */
    public function deleteDatesWeekly(int $id): bool;
}
