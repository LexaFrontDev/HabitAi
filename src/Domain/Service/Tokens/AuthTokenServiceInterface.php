<?php

namespace App\Domain\Service\Tokens;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

interface AuthTokenServiceInterface
{
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
