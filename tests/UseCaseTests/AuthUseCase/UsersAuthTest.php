<?php

namespace App\Tests\UseCaseTests\AuthUseCase;


use App\Aplication\Dto\UsersDto\UsersForLogin;
use App\Aplication\Dto\UsersDto\UsersForRegister;
use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Aplication\UseCase\AuthUseCase\UsersAuth;
use App\Domain\Entity\Users;
use App\Domain\Repository\Users\UsersRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UsersAuthTest extends TestCase
{
    public function testLoginSuccess(): void
    {
        // ==========================
        // Arrange
        // ==========================
        $user = new Users();
        $user->setId(1);
        $user->setEmail('test@example.com');
        $user->setName('John');
        $user->setPassword('hashed');


        $userRepo = $this->createMock(UsersRepositoryInterface::class);
        $userRepo->method('findByEmail')->willReturn($user);

        $hasher = $this->createMock(UserPasswordHasherInterface::class);
        $hasher->method('isPasswordValid')->willReturn(true);

        $jwtService = $this->createMock(JwtServicesInterface::class);
        $jwtService->method('generateJwtToken')->willReturn(new JwtTokenDto('access_token', 'refresh_token'));

        $service = new UsersAuth($userRepo, $jwtService, $hasher);

        $dto = new UsersForLogin('test@example.com', 'plain_pass');

        // ==========================
        // Act
        // ==========================
        $token = $service->login($dto);

        // ==========================
        // Assert
        // ==========================
        $this->assertInstanceOf(JwtTokenDto::class, $token);
        $this->assertEquals('access_token', $token->getAccessToken());
    }

    public function testLoginInvalidPassword(): void
    {
        // ==========================
        // Arrange
        // ==========================
        $user = new Users();
        $user->setId(1);
        $user->setEmail('test@example.com');
        $user->setName('John');
        $user->setPassword('hashed');


        $userRepo = $this->createMock(UsersRepositoryInterface::class);
        $userRepo->method('findByEmail')->willReturn($user);

        $hasher = $this->createMock(UserPasswordHasherInterface::class);
        $hasher->method('isPasswordValid')->willReturn(false);

        $jwtService = $this->createMock(JwtServicesInterface::class);
        $service = new UsersAuth($userRepo, $jwtService, $hasher);

        $dto = new UsersForLogin('test@example.com', 'wrong_pass');

        // ==========================
        // Act + Assert
        // ==========================
        $this->expectException(AuthenticationException::class);
        $service->login($dto);
    }

    public function testRegisterSuccess(): void
    {
        // ==========================
        // Arrange
        // ==========================
        $userRepo = $this->createMock(UsersRepositoryInterface::class);
        $userRepo->method('findByEmail')->willReturn(false);

        $userRepo->method('createUser')->willReturn(123);

        $hasher = $this->createMock(UserPasswordHasherInterface::class);
        $hasher->method('hashPassword')->willReturn('hashed_pw');

        $jwtService = $this->createMock(JwtServicesInterface::class);
        $jwtService->method('generateJwtToken')->willReturn(new JwtTokenDto('access_token', 'refresh_token'));

        $service = new UsersAuth($userRepo, $jwtService, $hasher);

        $dto = new UsersForRegister('user@example.com', 'John', 'pass123');

        // ==========================
        // Act
        // ==========================
        $token = $service->register($dto);

        // ==========================
        // Assert
        // ==========================
        $this->assertInstanceOf(JwtTokenDto::class, $token);
        $this->assertEquals('access_token', $token->getAccessToken());
    }

    public function testRegisterEmailExists(): void
    {
        // ==========================
        // Arrange
        // ==========================
        $userRepo = $this->createMock(UsersRepositoryInterface::class);
        $userRepo->method('findByEmail')->willReturn(new Users());

        $hasher = $this->createMock(UserPasswordHasherInterface::class);
        $jwtService = $this->createMock(JwtServicesInterface::class);

        $service = new UsersAuth($userRepo, $jwtService, $hasher);
        $dto = new UsersForRegister('user@example.com', 'John', 'pass123');

        // ==========================
        // Act + Assert
        // ==========================
        $this->expectException(\RuntimeException::class);
        $service->register($dto);
    }
}
