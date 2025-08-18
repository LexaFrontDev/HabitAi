<?php

namespace App\Domain\Repository\Dates;

use App\Aplication\Dto\DatesDto\ReqDataJunction;
use App\Domain\Entity\JunctionTabels\Habits\HabitsDataJuntion;
use App\Domain\Exception\ExistException\ExistException;
use App\Domain\Exception\NotFoundException\NotFoundException;

interface DataJunctionRepositoryInterface
{
    /**
     * @throws ExistException
     */
    public function saveDateJunction(ReqDataJunction $data): int;

    public function getDateJunctionByHabitsId(int $habitsId): ?HabitsDataJuntion;

    /**
     * Получить тип привычки по ID
     *
     * @throws NotFoundException
     */
    public function getTypeByHabitsId(int $habitsId): string;

    /**
     * @throws NotFoundException
     */
    public function updateDateJunction(int $habitsId, string $types): int|bool;

    /**
     * @throws NotFoundException
     */
    public function updateType(int $habitsId, string $types): bool;

    /**
     * @throws ExistException
     */
    public function createJunction(int $habitsId, string $types, int $dataId): int|bool;

    /**
     * @throws  NotFoundException
     */
    public function updateDataTypeAndId(int $id, int $habitsId, string $types, int $newDataId): int;
}
