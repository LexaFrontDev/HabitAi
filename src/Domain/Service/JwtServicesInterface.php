<?php

namespace App\Domain\Service;

use App\Aplication\Dto\JwtDto\JwtCheckDto;
use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Aplication\Dto\JwtDto\JwtTokenDto;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

interface JwtServicesInterface
{
    public function generateJwtToken(UsersInfoForToken $usersInfoForToken): JwtTokenDto;

    public function generateAccessToken(UsersInfoForToken $usersInfoForToken): string;

    public function generateRefreshToken(UsersInfoForToken $usersInfoForToken): string;

    public function refreshToken(string $refreshToken): JwtTokenDto;


    /**
     * @return JwtTokenDto|array
     * @throws AuthenticationException
     */
    public function validateToken(JwtCheckDto $tokens): JwtTokenDto|array;
}
