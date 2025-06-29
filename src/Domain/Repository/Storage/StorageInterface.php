<?php

namespace App\Domain\Repository\Storage;

use App\Aplication\Dto\StorageDto\SaveStorageDto;
use App\Aplication\Dto\StorageDto\UpdateStorageData;

interface StorageInterface
{


    /**
     * Сохраняет память по full path по type
     * @param SaveStorageDto $saveStorageDto
     * @return int|false
     */
    public function saveStorage(SaveStorageDto $saveStorageDto): int|false;


    /**
     * Обновляет память по идентифкатору
     * @param UpdateStorageData $saveStorageDto
     * @return int|false
     */
    public function updateStorage(UpdateStorageData $saveStorageDto): int|false;


    /**
     * Удаляет память по идентификатору
     * @param int $id
     * @return bool
     */
    public function deletePathById(int $id): bool;

}