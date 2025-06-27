<?php

namespace App\Tests\ControllerTests\UsersControllers;

use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Aplication\UseCase\UsersUseCase\UsersQueryUseCase;
use App\Infrastructure\Controller\ApiControllers\UsersControllers\UsersController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UsersControllerTest extends TestCase
{
    public function testGetUserIdSuccess(): void
    {
        // ==========================
        // Arrange
        // ==========================
        $userInfo = new UsersInfoForToken(1, 'John', 'john@example.com', 'user');

        $useCase = $this->createMock(UsersQueryUseCase::class);
        $useCase->method('getUsersInfoByToken')->willReturn($userInfo);

        $controller = new UsersController($useCase);
        $request = new Request();

        // ==========================
        // Act
        // ==========================
        $response = $controller->getUserId($request);

        // ==========================
        // Assert
        // ==========================
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(1, $data['userId']);
    }


    public function testGetUserIdUnauthorized(): void
    {
        // ==========================
        // Arrange
        // ==========================
        $useCase = $this->createMock(UsersQueryUseCase::class);
        $useCase->method('getUsersInfoByToken')
            ->willThrowException(new AuthenticationException());

        $controller = new UsersController($useCase);
        $request = new Request();

        // ==========================
        // Act
        // ==========================
        $response = $controller->getUserId($request);

        // ==========================
        // Assert
        // ==========================
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(401, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(['error' => 'Вы не авторизованы'], $data);
    }
}
