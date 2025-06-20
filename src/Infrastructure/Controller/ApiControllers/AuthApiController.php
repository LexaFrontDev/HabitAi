<?php

namespace App\Infrastructure\Controller\ApiControllers;

use App\Aplication\Dto\UsersDto\UsersForLogin;
use App\Aplication\Dto\UsersDto\UsersForRegister;
use App\Aplication\UseCase\AuthUseCase\UsersAuth;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class AuthApiController extends AbstractController
{
    public function __construct(
        private readonly UsersAuth $usersAuth
    ) {}

    #[Route('/api/auth/register', name: 'api_register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload]
        UsersForRegister $dto
    ): JsonResponse {
        $tokens = $this->usersAuth->register($dto);
        $response = new JsonResponse(['message' => 'Registered']);
        $this->setAuthCookies($response, $tokens->getAccessToken(), $tokens->getRefreshToken());
        return $response;
    }

    #[Route('/api/auth/login', name: 'api_login', methods: ['POST'])]
    public function login(
        #[MapRequestPayload]
        UsersForLogin $dto
    ): JsonResponse {
        $tokens = $this->usersAuth->login($dto);
        $response = new JsonResponse([
            'message' => 'Logged in',
            'access_token' => $tokens->getAccessToken(),
            'refresh_token' => $tokens->getRefreshToken()
        ], 200);
        $this->setAuthCookies($response, $tokens->getAccessToken(), $tokens->getRefreshToken());

        return $response;
    }

    private function setAuthCookies(JsonResponse $response, string $accessToken, string $refreshToken): void
    {
        $expire = strtotime('+7 days');

        $response->headers->setCookie(Cookie::create(
            'accessToken',
            $accessToken,
            $expire,
            '/',
            null,
            false,
            true,
            false,
            Cookie::SAMESITE_STRICT
        ));

        $response->headers->setCookie(Cookie::create(
            'refreshToken',
            $refreshToken,
            $expire,
            '/',
            null,
            false,
            true,
            false,
            Cookie::SAMESITE_STRICT
        ));
    }
}
