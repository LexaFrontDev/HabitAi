<?php

namespace App\Aplication\Dto\StorageDto;

class UpdateStorageData
{
    public function __construct(
        public readonly int $id,
        public readonly string $fullPath,
        public readonly string $type,
        public readonly string $fileType,
    ) {
    }


}
