<?php

namespace App\Infrastructure\Controller\ApiControllers\UsersControllers;

use App\Aplication\Dto\Notfication\ReqWebNotificationSubscriptions;
use App\Aplication\UseCase\UsersUseCase\UsersQueryUseCase;
use App\Infrastructure\Attribute\RequiresJwt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UsersController extends AbstractController
{
    public function __construct(private UsersQueryUseCase $usersQueryUseCase)
    {
    }

    #[Route('/api/web/user/id', name: 'get_users_id', methods: ['GET'])]
    #[RequiresJwt]
    public function getUserId(): JsonResponse
    {
        $userInfo = $this->usersQueryUseCase->getUsersInfoByToken();

        return new JsonResponse(['userId' => $userInfo->getUserId()], 200);
    }

    #[Route('/api/save/subscription/web', name: 'save_subscription', methods: ['POST'])]
    #[RequiresJwt]
    public function saveSubscription(#[MapRequestPayload] ReqWebNotificationSubscriptions $dto): JsonResponse
    {
        $userInfo = $this->usersQueryUseCase->saveSubscription($dto);

        return new JsonResponse(['success' => true], 200);
    }
}
