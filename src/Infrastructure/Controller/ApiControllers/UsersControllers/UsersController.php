<?php

namespace App\Infrastructure\Controller\ApiControllers\UsersControllers;

use App\Aplication\UseCase\UsersUseCase\UsersQueryUseCase;
use App\Infrastructure\Attribute\RequiresJwt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UsersController extends AbstractController
{
    public function __construct(private UsersQueryUseCase $usersQueryUseCase)
    {
    }

    #[Route('/api/web/user/id', name: 'get_users_id', methods: ['GET'])]
    #[RequiresJwt]
    public function getUserId(): JsonResponse
    {
        try {
            $userInfo = $this->usersQueryUseCase->getUsersInfoByToken();

            return new JsonResponse([
                'userId' => $userInfo->getUserId(),
            ], 200);
        } catch (AuthenticationException $e) {
            return new JsonResponse(['error' => 'Вы не авторизованы'], 401);
        }
    }
}
