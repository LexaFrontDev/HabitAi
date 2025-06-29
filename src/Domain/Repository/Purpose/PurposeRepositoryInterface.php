<?php

namespace App\Domain\Repository\Purpose;

use App\Aplication\Dto\PurposeDto\PurposeDto;

interface PurposeRepositoryInterface
{

    /**
     * @param PurposeDto $purpose
     * @return int|bool
     */
    public function savePurpose(PurposeDto $purpose): int|bool;

    /**
     * @param int $habitId
     * @return int
     */
    public function getPurposeCountByHabitId(int $habitId): int;

    /**
     * @param int $habitId
     * @param PurposeDto $dto
     * @return bool
     */
    public function updatePurposeByHabitId(PurposeDto $dto): bool;

    public function deletePurposeByHabitId(int $habitId): bool;
}