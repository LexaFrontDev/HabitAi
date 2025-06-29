<?php

namespace App\Infrastructure\Controller\ApiControllers;

use App\Aplication\Dto\JwtDto\JwtCheckDto;
use App\Aplication\Dto\UsersDto\UsersForLogin;
use App\Aplication\Dto\UsersDto\UsersForRegister;
use App\Aplication\UseCase\AuthUseCase\UsersAuth;
use App\Aplication\UseCase\Service\JwtTokens\JwtUseCase;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class AuthApiController extends AbstractController
{
    public function __construct(
        private readonly UsersAuth $usersAuth,
        private JwtUseCase $jwtAuth
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

    #[Route('/api/auth/check', name: 'api_check_auth', methods: ['GET'])]
    public function checkAuth(Request $request): JsonResponse
    {
        $accessToken = $request->cookies->get('accessToken') ?? $request->headers->get('accessToken');
        $refreshToken = $request->cookies->get('refreshToken') ?? $request->headers->get('refreshToken');

        if (!$accessToken || !$refreshToken) {
            return new JsonResponse(['message' => 'not authenticated'], 401);
        }
        $dto = new JwtCheckDto($accessToken, $refreshToken);
        $isValid = $this->jwtAuth->checkJwtToken($dto);


        if ($isValid) {
            return new JsonResponse(['message' => 'Authenticated'], 200);
        }

        return new JsonResponse(['message' => 'not authenticated'], 401);
    }


    #[Route('/api/logout', name: 'api_logout', methods: ['GET'])]
    public function logout(): RedirectResponse
    {
        $response = $this->redirect('/');

        $response->headers->clearCookie('accessToken', '/');
        $response->headers->clearCookie('refreshToken', '/');

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
