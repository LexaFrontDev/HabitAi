<?php

namespace App\Aplication\Dto\UsersDto;

use App\Domain\ValueObject\Settings\SettingsUsersValueObject;

class UsersForUpdate
{
    public function __construct(
        private readonly ?string $name = null,
        private readonly ?string $email = null,
        private readonly ?string $password = null,
        private readonly ?int $premium = null,
        private readonly ?string $role = null,
        private readonly ?SettingsUsersValueObject $settings = null,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getPremium(): ?int
    {
        return $this->premium;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getSettings(): ?SettingsUsersValueObject
    {
        return $this->settings;
    }
}
