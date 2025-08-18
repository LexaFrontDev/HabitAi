<?php

namespace App\Aplication\Dto\UsersDto;

class UsersInfoForToken
{
    public function __construct(
        private readonly int $userId,
        private readonly string $name,
        private readonly string $email,
        private readonly string $role = 'user',
        private readonly string $accToken = '',
        private readonly string $refToken = '',
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getAccToken(): string
    {
        return $this->accToken;
    }

    public function getRefToken(): string
    {
        return $this->refToken;
    }
}
