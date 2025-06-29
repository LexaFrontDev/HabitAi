<?php

namespace App\Domain\Repository\PurposeRepository;

use App\Aplication\Dto\PurposeDto\PurposeDto;

interface PurposeRepositoryInterface
{


    public function savePurpose(PurposeDto $purpose): int|bool;

    public function getPurposeCountByHabitId(int $habitId): int;

}