<?php

namespace App\Domain\Service\PasswordService;

interface PasswordHasherInterface
{
    /**
     * Хеширует пароль.
     */
    public function hash(string $plainPassword): string;

    /**
     * Проверяет, соответствует ли пароль хешу.
     */
    public function validate(string $plainPassword, string $hashedPassword): bool;
}
