<?php

namespace App\Domain\Service;

use App\Aplication\Dto\JwtDto\JwtCheckDto;
use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Aplication\Dto\JwtDto\JwtTokenDto;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

interface JwtServicesInterface
{
    public function generateJwtToken(UsersInfoForToken $usersInfoForToken): JwtTokenDto;

    public function generateAccessToken(UsersInfoForToken $usersInfoForToken): string;

    public function generateRefreshToken(UsersInfoForToken $usersInfoForToken): string;

    public function refreshToken(string $refreshToken): JwtTokenDto;


    /**
     * @return JwtTokenDto|bool
     * @throws AuthenticationException
     */
    public function validateToken(JwtCheckDto $tokens): JwtTokenDto|bool;

    public function getUserInfoFromToken(string $token): UsersInfoForToken;

    /**
     *
     * @param Request $request
     * @return array ['accessToken' => string|null, 'refreshToken' => string|null]
     */
    public function getTokens(Request $request): array;


    /**
     * @param string $accessToken
     * @param string $refreshToken
     * @param Response $response
     * @return string статус токенов
     */
    public function handleTokens(string $accessToken, string $refreshToken, Response $response): string;

}
