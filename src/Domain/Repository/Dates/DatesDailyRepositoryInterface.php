<?php

namespace App\Domain\Repository\Dates;

use App\Aplication\Dto\DatesDto\DatesDailyForSaveDto;
use App\Domain\Exception\ExistException\ExistException;
use App\Domain\Exception\NotFoundException\NotFoundException;

interface DatesDailyRepositoryInterface
{

    /**
     * @param DatesDailyForSaveDto $dates
     * @return int
     */
    public function saveDates(DatesDailyForSaveDto $dates): int;

    public function updateDates(int $id, DatesDailyForSaveDto $dates): bool;

    /**
     * @param int $id
     * @return bool
     * @throws NotFoundException
     */
    public function deleteDatesDaily(int $id): bool;
}