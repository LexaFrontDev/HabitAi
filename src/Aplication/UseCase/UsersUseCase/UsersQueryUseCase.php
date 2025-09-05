<?php

namespace App\Aplication\UseCase\UsersUseCase;

use App\Aplication\Dto\Notfication\RepNotificationSubscriptionCreate;
use App\Aplication\Dto\Notfication\ReqWebNotificationSubscriptions;
use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Domain\Exception\Message\MessageException;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Notification\NotificationSubscriptionInterface;
use App\Domain\Service\JwtServicesInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UsersQueryUseCase
{
    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private JwtServicesInterface $jwtServices,
        private NotificationSubscriptionInterface $notificationSubscription,
    ) {
    }

    /**
     * @throws AuthenticationException
     */
    public function getUsersInfoByToken(): UsersInfoForToken
    {
        $tokens = $this->tokenProvider->getTokens();

        if (empty($tokens->getAccessToken())) {
            throw new AuthenticationException('Access token is missing or invalid.');
        }

        return $this->jwtServices->getUserInfoFromToken($tokens->getAccessToken());
    }

    public function saveSubscription(ReqWebNotificationSubscriptions $dto): bool
    {

        $userId = $this->getUsersInfoByToken()->getUserId();

        $dto = new RepNotificationSubscriptionCreate(
            userId: $userId,
            platform: $dto->platform,
            endpoint: $dto->endpoint,
            keys: $dto->keys,
        );

        $result = $this->notificationSubscription->saveNotificationSubscription($dto);
        if (empty($result)) {
            throw new MessageException('Не удалось сохранить данные для пуш уведомление');
        }

        return true;
    }
}
