<?php

namespace App\Domain\Repository\Purpose;

use App\Aplication\Dto\PurposeDto\PurposeDto;

interface PurposeRepositoryInterface
{
    public function savePurpose(PurposeDto $purpose): int|bool;

    public function getPurposeCountByHabitId(int $habitId): int;

    public function updatePurposeByHabitId(PurposeDto $dto): bool;

    public function deletePurposeByHabitId(int $habitId): bool;
}
