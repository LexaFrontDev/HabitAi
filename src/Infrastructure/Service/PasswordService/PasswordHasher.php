<?php

namespace App\Infrastructure\Service\PasswordService;

use App\Domain\Service\PasswordService\PasswordHasherInterface;

class PasswordHasher implements PasswordHasherInterface
{
    public function hash(string $plainPassword): string
    {
        if (empty($plainPassword)) {
            throw new \InvalidArgumentException('Password cannot be empty.');
        }

        return password_hash($plainPassword, PASSWORD_ARGON2ID);
    }

    public function validate(string $plainPassword, string $hashedPassword): bool
    {
        if (empty($hashedPassword) || empty($plainPassword)) {
            return false;
        }

        return password_verify($plainPassword, $hashedPassword);
    }
}
