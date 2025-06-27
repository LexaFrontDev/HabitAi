<?php

namespace App\Domain\Repository\DatesRepository;

use App\Aplication\Dto\DatesDto\DatesDailyForSaveDto;

interface DatesDailyRepositoryInterface
{

    public function saveDates(DatesDailyForSaveDto $dates): int|bool;



}