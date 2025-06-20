<?php

namespace App\Domain\Service\Tokens;

use Symfony\Component\HttpFoundation\Response;

interface AuthTokenServiceInterface
{
    /**
     * @param string $accessToken
     * @param string $refreshToken
     * @param Response $response
     * @return string статус токенов
     */
    public function handleTokens(string $accessToken, string $refreshToken, Response $response): string;
}
