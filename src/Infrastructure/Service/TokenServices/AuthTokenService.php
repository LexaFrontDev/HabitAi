<?php

namespace App\Infrastructure\Service\TokenServices;

use App\Aplication\Dto\JwtDto\JwtCheckDto;
use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Aplication\UseCase\Service\JwtTokens\JwtUseCase;
use App\Domain\Service\Tokens\AuthTokenServiceInterface;
use MongoDB\Driver\Exception\AuthenticationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class AuthTokenService implements AuthTokenServiceInterface
{
    public function __construct(
        private JwtUseCase $jwtUseCase,
        private LoggerInterface $logger,
    ) {}








    /**
     * @param string   $accessToken
     * @param string   $refreshToken
     * @param Response $response
     * @return string status
     */
    public function handleTokens(
        string $accessToken,
        string $refreshToken,
        Response $response
    ): string {


        if (empty($accessToken) || empty($refreshToken)) {
            $this->logger->error('Отсутствует токен в куках.');
            return 'invalid';
        }

        $dto = new JwtCheckDto($refreshToken, $accessToken);


        try {
            $result = $this->jwtUseCase->checkJwtToken($dto);

            if ($result instanceof JwtTokenDto) {
                $this->setTokens($response, $result);
                return 'new_tokens';
            }

            if ($result === true) {
                return 'valid_tokens';
            }
        } catch (\Throwable $e) {
            $this->logger->error('Ошибка при обработке токенов', [
                'exception' => $e,
                'message' => $e->getMessage(),
            ]);
        }

        return 'invalid';
    }

    private function setTokens(Response $response, JwtTokenDto $tokens): void
    {
        $accessCookie = Cookie::create(
            'accessToken',
            $tokens->getAccessToken(),
            strtotime('+7 days'),
            '/',
            null,
            false,
            true,
            false,
            Cookie::SAMESITE_STRICT
        );

        $refreshCookie = Cookie::create(
            'refreshToken',
            $tokens->getRefreshToken(),
            strtotime('+7 days'),
            '/',
            null,
            false,
            true,
            false,
            Cookie::SAMESITE_STRICT
        );

        $response->headers->setCookie($accessCookie);
        $response->headers->setCookie($refreshCookie);
    }

    /**
     * @param Request $request
     * @return array{accessToken: string, refreshToken: string}
     * @throws \RuntimeException
     */
    public function getTokens(Request $request): array
    {
        $accessToken = $request->cookies->get('accessToken')
            ?? $request->headers->get('access-token');

        $refreshToken = $request->cookies->get('refreshToken')
            ?? $request->headers->get('refresh-token');

        if (empty($accessToken) || empty($refreshToken)) {
            throw new \RuntimeException('Missing access or refresh token');
        }

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
        ];
    }




}


