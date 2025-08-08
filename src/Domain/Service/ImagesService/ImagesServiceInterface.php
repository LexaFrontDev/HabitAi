<?php


namespace App\Domain\Service\ImagesService;

interface ImagesServiceInterface
{
    /**
     * Сохраняет изображение по base64
     *
     * @param string $imageType Тип изображения (например: Users, Pomodoro и т.д.)
     * @param string $image64   Данные изображения в base64
     * @return bool
     */
    public function saveImage(string $image64): bool;

    /**
     * Удаляет изображение по imageId (переносит в папку Archive)
     *
     * @param string|int $imageId
     * @return bool
     */
    public function deleteImage(string|int $imageId): bool;

    /**
     * Переносит изображение в другую папку или категорию
     * (например, при изменении типа или архивации вручную)
     *
     * @param string|int $imageId
     * @param string $targetFolder
     * @return bool
     */
    public function moveImage(string|int $imageId, string $targetFolder): bool;
}
