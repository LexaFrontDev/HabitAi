<?php

namespace App\Aplication\UseCase\UsersUseCase;

use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\Tokens\AuthTokenServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UsersQueryUseCase
{
    public function __construct(
        private AuthTokenServiceInterface $authTokenService,
        private JwtServicesInterface $jwtServices
    ) {}

    /**
     *
     * @param Request $request
     * @return UsersInfoForToken
     *
     * @throws AuthenticationException
     */
    public function getUsersInfoByToken(Request $request): UsersInfoForToken
    {
        $tokens = $this->authTokenService->getTokens($request);

        if (empty($tokens['accessToken'])) {
            throw new AuthenticationException('Access token is missing or invalid.');
        }

        return $this->jwtServices->getUserInfoFromToken($tokens['accessToken']);
    }
}
