<?php

namespace App\Domain\Repository\Users;

use App\Aplication\Dto\UsersDto\UsersForRegister;
use App\Aplication\Dto\UsersDto\UsersForUpdate;
use App\Domain\Entity\Users;

interface UsersRepositoryInterface
{

    public function findByEmail(string $email): Users|bool;
    public function findById(int $id): Users|bool;
    public function updateUsersInfoByEmail(string $email, UsersForUpdate $userInfo): bool;
    public function createUser(Users $user): int|bool;
}