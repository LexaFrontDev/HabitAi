<?php

namespace App\Infrastructure\Service\ImageServices;

use App\Aplication\Dto\StorageDto\SaveStorageDto;
use App\Aplication\Dto\StorageDto\UpdateStorageData;
use App\Domain\Repository\Storage\StorageInterface;
use App\Domain\Service\ImagesService\ImagesServiceInterface;

class ImageServise implements ImagesServiceInterface
{
    public function __construct(
        private StorageInterface $storage,
    ) {
    }

    public function saveImage(string $image64): bool
    {
        $uploadDir = __DIR__.'/../../../../public/Upload/Images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $image64, $typeMatch)) {
            $extension = strtolower($typeMatch[1]);
            $image64 = substr($image64, strpos($image64, ',') + 1);
        } else {
            return false;
        }

        $filename = uniqid('img_', true).'.'.$extension;
        $fullPath = $uploadDir.$filename;
        $imageData = base64_decode($image64, true);
        if (false === $imageData) {
            return false;
        }

        if (false === file_put_contents($fullPath, $imageData)) {
            return false;
        }


        $dto = new SaveStorageDto(
            fullPath: 'Upload/Images/'.$filename,
            type: 'image',
            fileType: $extension
        );

        return (bool) $this->storage->saveStorage($dto);
    }

    public function deleteImage(string|int $imageId): bool
    {
        return $this->storage->deletePathById((int) $imageId);
    }

    public function moveImage(string|int $imageId, string $targetFolder): bool
    {
        $storage = $this->storage->fineByFileId((int) $imageId);
        if (!$storage) {
            return false;
        }

        $sourcePath = __DIR__.'/../../../../public/'.$storage->getFullPath();
        $targetDir = __DIR__.'/../../../../public/'.$targetFolder;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filename = basename($storage->getFullPath());
        $newRelativePath = trim($targetFolder, '/').'/'.$filename;
        $newFullPath = $targetDir.'/'.$filename;

        if (!file_exists($sourcePath)) {
            return false;
        }

        if (!rename($sourcePath, $newFullPath)) {
            return false;
        }

        $dto = new UpdateStorageData(
            id: $storage->getId(),
            fullPath: $newRelativePath,
            type: $storage->getType(),
            fileType: $storage->getFileType()
        );

        return (bool) $this->storage->updateStorage($dto);
    }
}
