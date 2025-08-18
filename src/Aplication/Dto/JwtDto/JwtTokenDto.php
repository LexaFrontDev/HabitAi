<?php

namespace App\Aplication\Dto\JwtDto;

class JwtTokenDto
{
    public function __construct(
        private string $accessToken,
        private string $refreshToken,
    ) {
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
