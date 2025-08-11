<?php

namespace App\Aplication\UseCase\UsersUseCase;

use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Service\JwtServicesInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UsersQueryUseCase
{
    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private JwtServicesInterface $jwtServices
    ) {}

    /**
     * @return UsersInfoForToken
     *
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
}
