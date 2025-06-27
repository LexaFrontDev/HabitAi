<?php

namespace App\Domain\Repository\DayesRepeatRepository;

interface DatesRepeatRepositoryInterface
{


    public function saveDatesRepeat(int $day): int;


}