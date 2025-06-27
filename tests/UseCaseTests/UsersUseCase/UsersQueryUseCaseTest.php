<?php

namespace App\Tests\UseCaseTests\UsersUseCase;

use App\Aplication\UseCase\UsersUseCase\UsersQueryUseCase;
use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\Tokens\AuthTokenServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UsersQueryUseCaseTest extends TestCase
{
    public function testGetUsersInfoByTokenSuccess(): void
    {
        // ==========================
        // Arrange
        // ==========================
        $expectedUserInfo = new UsersInfoForToken(1, 'John', 'john@example.com', 'user');

        $authTokenService = $this->createMock(AuthTokenServiceInterface::class);
        $authTokenService->method('getTokens')
            ->willReturn(['accessToken' => 'fake_access_token', 'refreshToken' => 'fake_refresh_token']);

        $jwtService = $this->createMock(JwtServicesInterface::class);
        $jwtService->method('getUserInfoFromToken')
            ->with('fake_access_token')
            ->willReturn($expectedUserInfo);

        $useCase = new UsersQueryUseCase($authTokenService, $jwtService);
        $request = new Request();

        // ==========================
        // Act
        // ==========================
        $userInfo = $useCase->getUsersInfoByToken($request);

        // ==========================
        // Assert
        // ==========================
        $this->assertSame($expectedUserInfo, $userInfo);
    }

    public function testGetUsersInfoByTokenMissingAccessToken(): void
    {
        // ==========================
        // Arrange
        // ==========================
        $authTokenService = $this->createMock(AuthTokenServiceInterface::class);
        $authTokenService->method('getTokens')->willReturn([]);

        $jwtService = $this->createMock(JwtServicesInterface::class);

        $useCase = new UsersQueryUseCase($authTokenService, $jwtService);
        $request = new Request();

        // ==========================
        // Act + Assert
        // ==========================
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Access token is missing or invalid.');

        $useCase->getUsersInfoByToken($request);
    }
}
