<?php

namespace App\Domain\Service;

use App\Aplication\Dto\JwtDto\JwtCheckDto;
use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Aplication\Dto\JwtDto\JwtTokenDto;

interface JwtServicesInterface
{
    public function generateJwtToken(UsersInfoForToken $usersInfoForToken): JwtTokenDto;

    public function generateAccessToken(UsersInfoForToken $usersInfoForToken): string;

    public function generateRefreshToken(UsersInfoForToken $usersInfoForToken): string;

    public function refreshToken(string $refreshToken): JwtTokenDto;

    public function validateToken(JwtCheckDto $tokens): JwtTokenDto|bool;

    public function getUserInfoFromToken(string $token): UsersInfoForToken;

    /**
     * @return string статус токенов
     */
    public function handleTokens(string $accessToken, string $refreshToken): string;
}
