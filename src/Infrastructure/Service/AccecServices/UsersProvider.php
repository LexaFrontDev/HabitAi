<?php

namespace App\Infrastructure\Service\AccecServices;

use App\Domain\Exception\UsersException\UserNotAuthenticatedException;
use App\Domain\Service\Provider\UserProvidersInterfaceDomain;
use App\Domain\Entity\Users;
use App\Domain\Repository\Users\UsersRepositoryInterface;

class UsersProvider implements UserProvidersInterfaceDomain
{
    public function __construct(
        public UsersRepositoryInterface $usersRepository,
        private UsersRepositoryInterface $repository,
    ) {
    }

    public function loadUserByIdentifier(string $identifier): Users
    {
        $user = $this->repository->findByEmail($identifier);

        if (!$user instanceof Users) {
            throw new UserNotAuthenticatedException('Пользователь не найден');
        }

        return $user;
    }

    public function refreshUser(Users $user): Users
    {
        return $this->loadUserByIdentifier($user->getEmail());
    }

    public function supportsClass(string $class): bool
    {
        return Users::class === $class;
    }
}
