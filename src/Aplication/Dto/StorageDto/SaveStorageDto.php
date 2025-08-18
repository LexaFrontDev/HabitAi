<?php

namespace App\Aplication\Dto\StorageDto;

class SaveStorageDto
{
    public function __construct(
        public readonly string $fullPath,
        public readonly string $type,
        public readonly string $fileType,
    ) {
    }


}
