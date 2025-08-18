<?php

namespace App\Domain\Service\ImagesService;

interface ImagesServiceInterface
{
    /**
     * Сохраняет изображение по base64
     *
     * @param string $image64 Данные изображения в base64
     */
    public function saveImage(string $image64): bool;

    /**
     * Удаляет изображение по imageId (переносит в папку Archive)
     */
    public function deleteImage(string|int $imageId): bool;

    /**
     * Переносит изображение в другую папку или категорию
     * (например, при изменении типа или архивации вручную)
     */
    public function moveImage(string|int $imageId, string $targetFolder): bool;
}
