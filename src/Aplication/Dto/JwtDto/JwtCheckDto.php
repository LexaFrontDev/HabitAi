<?php

namespace App\Aplication\Dto\JwtDto;

class JwtCheckDto
{
    public function __construct(
        private  string $refreshToken,
        private  string $accessToken,
    ){}

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }


    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

}