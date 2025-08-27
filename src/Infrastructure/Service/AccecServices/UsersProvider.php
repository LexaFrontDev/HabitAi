<?php

namespace App\Infrastructure\Service\AccecServices;

use App\Domain\Exception\UsersException\UserNotAuthenticatedException;
use App\Domain\Port\RequestTokenInterface;
use App\Domain\Service\Provider\UserProvidersInterfaceDomain;
use App\Domain\Entity\Users;
use App\Domain\Repository\Users\UsersRepositoryInterface;

class UsersProvider implements UserProvidersInterfaceDomain
{
    public function __construct(
        private RequestTokenInterface $tokenResponseSetter,
        public UsersRepositoryInterface $usersRepository,
        private UsersRepositoryInterface $repository,
    ) {
    }

    /**
     * @throws  UserNotAuthenticatedException
     */
    public function loadUserByIdentifier(string $identifier): Users
    {
        $user = $this->repository->findByEmail($identifier);

        if (empty($user) || !$user instanceof Users) {
            $this->tokenResponseSetter->clearTokens();
            throw new UserNotAuthenticatedException('UserProvider returned invalid type');
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
