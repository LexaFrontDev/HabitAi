<?php

namespace App\Aplication\UseCase\AuthUseCase;

use App\Aplication\Dto\UsersDto\UsersForLogin;
use App\Aplication\Dto\UsersDto\UsersForRegister;
use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Domain\Entity\Users;
use App\Domain\Exception\Message\MessageException;
use App\Domain\Exception\UsersException\UserNotAuthenticatedException;
use App\Domain\Repository\Users\UsersRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\PasswordService\PasswordHasherInterface;

class UsersAuth
{
    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
        private readonly JwtServicesInterface $jwtServices,
        private readonly PasswordHasherInterface $passwordHasher,
    ) {
    }

    public function login(UsersForLogin $users): JwtTokenDto
    {
        $email = $users->getEmail();
        $plainPassword = $users->getPassword();

        $user = $this->usersRepository->findByEmail($email);

        if (!$user instanceof Users || !$this->passwordHasher->validate($plainPassword, $user->getPassword())) {
            throw new UserNotAuthenticatedException('Invalid email or password.');
        }

        $tokenData = new UsersInfoForToken(
            userId: $user->getId(),
            name: $user->getName(),
            email: $user->getEmail()
        );

        return $this->jwtServices->generateJwtToken($tokenData);
    }

    public function register(UsersForRegister $users): JwtTokenDto
    {
        $existingUser = $this->usersRepository->findByEmail($users->getEmail());

        if ($existingUser instanceof Users) {
            throw new MessageException('User with this email already exists.');
        }

        $userEntity = new Users();
        $userEntity->setEmail($users->getEmail());
        $userEntity->setName($users->getName());
        $hashedPassword = $this->passwordHasher->hash($users->getPassword());
        $userEntity->setPassword($hashedPassword);
        $userEntity->setRole($users->getRole() ?: 'user');
        $userEntity->setPremium($users->getPremium());

        $userId = $this->usersRepository->createUser($userEntity);

        if (!is_int($userId) || $userId <= 0) {
            throw new \RuntimeException('Registration failed.');
        }

        $tokenData = new UsersInfoForToken(
            userId: $userId,
            name: $userEntity->getName(),
            email: $userEntity->getEmail()
        );

        return $this->jwtServices->generateJwtToken($tokenData);
    }
}
