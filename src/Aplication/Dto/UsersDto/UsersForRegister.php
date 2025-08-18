<?php

namespace App\Aplication\Dto\UsersDto;

class UsersForRegister
{
    public function __construct(
        private string $name,
        private string $email,
        private string $password,
        private string $role = 'user',
        private int $premium = 1,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getPremium(): int
    {
        return $this->premium;
    }
}
