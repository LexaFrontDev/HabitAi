<?php

namespace App\Tests\ServiceTests\TokensServicesTest;


use App\Aplication\Dto\JwtDto\JwtCheckDto;
use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Domain\Service\JwtServicesInterface;
use PHPUnit\Framework\TestCase;

class JwtServicesInterfaceTest extends TestCase
{
    public function testGenerateJwtToken(): void
    {
        // Arrange
        $service = $this->createMock(JwtServicesInterface::class);
        $expected = new JwtTokenDto('access', 'refresh');
        $userInfo = new UsersInfoForToken(1, 'name', 'email@test.com');

        $service->method('generateJwtToken')->willReturn($expected);

        // Act
        $token = $service->generateJwtToken($userInfo);

        // Assert
        $this->assertSame($expected, $token);
    }

    public function testGenerateAccessToken(): void
    {
        // Arrange
        $service = $this->createMock(JwtServicesInterface::class);
        $userInfo = new UsersInfoForToken(1, 'name', 'email@test.com');
        $service->method('generateAccessToken')->willReturn('access_token');

        // Act
        $accessToken = $service->generateAccessToken($userInfo);

        // Assert
        $this->assertSame('access_token', $accessToken);
    }

    public function testGenerateRefreshToken(): void
    {
        // Arrange
        $service = $this->createMock(JwtServicesInterface::class);
        $userInfo = new UsersInfoForToken(1, 'name', 'email@test.com');
        $service->method('generateRefreshToken')->willReturn('refresh_token');

        // Act
        $refreshToken = $service->generateRefreshToken($userInfo);

        // Assert
        $this->assertSame('refresh_token', $refreshToken);
    }

    public function testRefreshToken(): void
    {
        // Arrange
        $service = $this->createMock(JwtServicesInterface::class);
        $expected = new JwtTokenDto('new_access', 'new_refresh');
        $service->method('refreshToken')->willReturn($expected);

        // Act
        $token = $service->refreshToken('old_refresh');

        // Assert
        $this->assertSame($expected, $token);
    }

    public function testValidateToken(): void
    {
        // Arrange
        $service = $this->createMock(JwtServicesInterface::class);
        $expected = new JwtTokenDto('access_token', 'refresh_token');
        $jwtCheckDto = new JwtCheckDto('access_token', 'refresh_token');
        $service->method('validateToken')->willReturn($expected);

        // Act
        $token = $service->validateToken($jwtCheckDto);

        // Assert
        $this->assertSame($expected, $token);
    }

    public function testGetUserInfoFromToken(): void
    {
        // Arrange
        $service = $this->createMock(JwtServicesInterface::class);
        $expected = new UsersInfoForToken(1, 'name', 'email@test.com');
        $service->method('getUserInfoFromToken')->willReturn($expected);

        // Act
        $userInfo = $service->getUserInfoFromToken('token');

        // Assert
        $this->assertSame($expected, $userInfo);
    }
}
