<?php

namespace App\Domain\Repository\DatesWeekly;

interface DatesWeeklyRepositoryInterface
{


    public function saveDatesWeekly(int $count): int|bool;

}