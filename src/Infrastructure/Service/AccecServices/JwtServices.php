<?php

namespace App\Infrastructure\Service\AccecServices;

use App\Aplication\Dto\JwtDto\JwtCheckDto;
use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Aplication\UseCase\Service\JwtTokens\JwtUseCase;
use App\Domain\Entity\Users;
use App\Domain\Repository\Users\UsersRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class JwtServices implements JwtServicesInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private JWTTokenManagerInterface $jwtManager,
        private JWTEncoderInterface $jwtEncoder,
        private UserProviderInterface $userProvider,
        private UsersRepositoryInterface $user,
        private string $refreshTokenTtl = '+7 days',
    ) {}

    public function generateJwtToken(UsersInfoForToken $usersInfoForToken): JwtTokenDto
    {
        $accessToken = $this->generateAccessToken($usersInfoForToken);
        $refreshToken = $this->generateRefreshToken($usersInfoForToken);
        return new JwtTokenDto($accessToken, $refreshToken);
    }

    public function generateAccessToken(UsersInfoForToken $usersInfoForToken): string
    {
        $user = new Users();
        $user->setEmail($usersInfoForToken->getEmail());
        $user->setName($usersInfoForToken->getName());
        $user->setRole($usersInfoForToken->getRole() ?? 'user');
        return $this->jwtManager->create($user);
    }

    public function generateRefreshToken(UsersInfoForToken $usersInfoForToken): string
    {
        $payload = [
            'email' => $usersInfoForToken->getEmail(),
            'exp' => (new DateTimeImmutable())->modify($this->refreshTokenTtl)->getTimestamp(),
            'type' => 'refresh',
        ];

        return $this->jwtEncoder->encode($payload);
    }

    public function refreshToken(string $refreshToken): JwtTokenDto
    {
        try {
            $payload = $this->jwtEncoder->decode($refreshToken);

            if (!isset($payload['email'], $payload['exp'], $payload['type']) || $payload['type'] !== 'refresh') {
                throw new AuthenticationException('Invalid refresh token payload.');
            }

            if ($payload['exp'] < time()) {
                throw new AuthenticationException('Refresh token has expired.');
            }

            $user = $this->userProvider->loadUserByIdentifier($payload['email']);

            $accessToken = $this->jwtManager->create($user);
            $newRefreshToken = $this->jwtEncoder->encode([
                'email' => $payload['email'],
                'exp' => (new DateTimeImmutable())->modify($this->refreshTokenTtl)->getTimestamp(),
                'type' => 'refresh',
            ]);

            return new JwtTokenDto($accessToken, $newRefreshToken);
        } catch (\Exception $e) {
            throw new AuthenticationException('Could not refresh token: ' . $e->getMessage());
        }
    }



    /**
     * @return JwtTokenDto|array
     * @throws AuthenticationException
     */
    public function validateToken(JwtCheckDto $tokens): JwtTokenDto|bool
    {
        try {
            $accessPayload = $this->decodeJwt($tokens->getAccessToken());

            if (!isset($accessPayload['exp']) || $accessPayload['exp'] < time()) {
                throw new \Exception('Access token expired');
            }

            return true;
        } catch (\Throwable) {
            try {
                $refreshPayload = $this->decodeJwt($tokens->getRefreshToken());

                if (
                    !isset($refreshPayload['exp'], $refreshPayload['email']) ||
                    $refreshPayload['exp'] < time() ||
                    ($refreshPayload['type'] ?? '') !== 'refresh'
                ) {
                    throw new AuthenticationException('Refresh token invalid');
                }

                $user = $this->userProvider->loadUserByIdentifier($refreshPayload['email']);

                return new JwtTokenDto(
                    $this->jwtManager->create($user),
                    $this->generateRefreshToken(new UsersInfoForToken(
                        $user->getUserId(),
                        $user->getName(),
                        $user->getEmail(),
                        $user->getRoles()[0] ?? 'user',
                    ))
                );
            } catch (\Throwable) {
                throw new AuthenticationException('Both tokens are invalid or expired');
            }
        }
    }



    private function decodeJwt(string $token): array
    {
        [$header, $payload, $signature] = explode('.', $token);

        $decoded = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
        if (!is_array($decoded)) {
            throw new \RuntimeException('Invalid JWT structure');
        }

        return $decoded;
    }




    public function getUserInfoFromToken(string $token): UsersInfoForToken
    {
        $payload = $this->decodeJwt($token);

        if (!isset($payload['username'])) {
            throw new AuthenticationException('Invalid access token.');
        }

        $result = $this->user->findByEmail($payload['username']);
        if (empty($result)) {
            throw new AuthenticationException("Такого пользователя не существует: " . $payload['username']);
        }
        $role = $result->getRoles();

        return new UsersInfoForToken(
            $result->getId(),
            $result->getName(),
            $result->getEmail(),
            $role[0] ?? 'user',
        );
    }



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
            $result = $this->validateToken($dto);

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
