<?php

namespace App\Domain\Repository\Dates;

use App\Aplication\Dto\DatesDto\ReqDataJunction;
use App\Domain\Entity\JunctionTabels\Habits\HabitsDataJuntion;
use App\Domain\Exception\ExistException\ExistException;
use App\Domain\Exception\NotFoundException\NotFoundException;

interface DataJunctionRepositoryInterface
{


    /**
     * @param ReqDataJunction $data
     * @return int
     * @throws ExistException
     */
    public function saveDateJunction(ReqDataJunction $data): int;

    /**
     * @param int $habitsId
     * @return HabitsDataJuntion|null
     */
    public function getDateJunctionByHabitsId(int $habitsId): ?HabitsDataJuntion;


    /**
     * Получить тип привычки по ID
     *
     * @param int $habitsId
     * @return string
     * @throws NotFoundException
     */
    public function getTypeByHabitsId(int $habitsId): string;



    /**
     * @param int $habitsId
     * @param string $types
     * @return int|bool
     * @throws NotFoundException
     */
    public function updateDateJunction(int $habitsId, string $types): int|bool;


    /**
     * @param int $habitsId
     * @param string $types
     * @return bool
     * @throws NotFoundException
     */
    public function updateType(int $habitsId, string $types): bool;

    /**
     * @param int $habitsId
     * @param string $types
     * @param int $dataId
     * @return int|bool
     * @throws ExistException
     */
    public function createJunction(int $habitsId, string $types, int $dataId): int|bool;


    /**
     * @param int $id
     * @param int $habitsId
     * @param string $types
     * @param int $newDataId
     * @return int
     * @throws  NotFoundException
     */
    public function updateDataTypeAndId(int $id, int $habitsId, string $types, int $newDataId): int;

}