<?php

namespace App\Domain\Repository\Storage;

use App\Aplication\Dto\StorageDto\SaveStorageDto;
use App\Aplication\Dto\StorageDto\UpdateStorageData;
use App\Domain\Entity\Storage\Storage;

interface StorageInterface
{
    public const IMAGE_TYPE = 1;
    public const FILE_TYPE = 2;

    /**
     * Сохраняет память по full path по type
     */
    public function saveStorage(SaveStorageDto $saveStorageDto): int|false;

    /**
     * Обновляет память по идентифкатору
     */
    public function updateStorage(UpdateStorageData $updateStorageDto): int|false;

    /**
     * Удаляет память по идентификатору
     */
    public function deletePathById(int $id): bool;

    public function fineByFileId(int $fileId): Storage|false;
}
