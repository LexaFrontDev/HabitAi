<?php

namespace App\Tests\ServiceTests\TokensServicesTest;


use App\Domain\Service\Tokens\AuthTokenServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthTokenServiceInterfaceTest extends TestCase
{
    public function testGetTokensFromRequest(): void
    {
        // Arrange
        $service = $this->createMock(AuthTokenServiceInterface::class);
        $service->method('getTokens')->willReturn([
            'accessToken' => 'mock_access',
            'refreshToken' => 'mock_refresh',
        ]);
        $request = $this->createMock(Request::class);

        // Act
        $tokens = $service->getTokens($request);

        // Assert
        $this->assertSame('mock_access', $tokens['accessToken']);
        $this->assertSame('mock_refresh', $tokens['refreshToken']);
    }

    public function testHandleTokensWithValidTokens(): void
    {
        // Arrange
        $service = $this->createMock(AuthTokenServiceInterface::class);
        $service->method('handleTokens')->willReturn('valid_tokens');
        $response = $this->createMock(Response::class);

        // Act
        $status = $service->handleTokens('access_token', 'refresh_token', $response);

        // Assert
        $this->assertSame('valid_tokens', $status);
    }

    public function testHandleTokensWithInvalidTokens(): void
    {
        // Arrange
        $service = $this->createMock(AuthTokenServiceInterface::class);
        $service->method('handleTokens')->willReturn('invalid');
        $response = $this->createMock(Response::class);

        // Act
        $status = $service->handleTokens('', '', $response);

        // Assert
        $this->assertSame('invalid', $status);
    }
}
