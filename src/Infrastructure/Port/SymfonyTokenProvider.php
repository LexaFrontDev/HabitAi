<?php

namespace App\Infrastructure\Port;

use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Domain\Exception\UsersException\UserNotAuthenticatedException;
use App\Domain\Port\TokenProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class SymfonyTokenProvider implements TokenProviderInterface
{
    public function __construct(private RequestStack $requestStack) {}

    public function getTokens(): JwtTokenDto
    {
        $request = $this->requestStack->getCurrentRequest();

        $accessToken = $request->cookies->get('accessToken')
            ?? $request->headers->get('access-token');

        $refreshToken = $request->cookies->get('refreshToken')
            ?? $request->headers->get('refresh-token');


        if (empty($accessToken) || empty($refreshToken)) {
            throw new UserNotAuthenticatedException('Missing access or refresh token');
        }


        return new JwtTokenDto(accessToken: $accessToken, refreshToken: $refreshToken);
    }
}