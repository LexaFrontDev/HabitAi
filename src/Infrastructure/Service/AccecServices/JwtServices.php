<?php

namespace App\Infrastructure\Service\AccecServices;

use App\Aplication\Dto\JwtDto\JwtCheckDto;
use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Domain\Entity\Users;
use App\Domain\Exception\UsersException\UserNotAuthenticatedException;
use App\Domain\Port\RequestTokenInterface;
use App\Domain\Repository\Users\UsersRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\Provider\UserProvidersInterfaceDomain;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;

class JwtServices implements JwtServicesInterface
{
    public function __construct(
        private RequestTokenInterface $tokenResponseSetter,
        private LoggerInterface $logger,
        private JWTTokenManagerInterface $jwtManager,
        private JWTEncoderInterface $jwtEncoder,
        private UserProvidersInterfaceDomain $userProvider,
        private UsersRepositoryInterface $user,
        private string $refreshTokenTtl = '+7 days',
    ) {
    }

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
        $user->setRole($usersInfoForToken->getRole());

        return $this->jwtManager->create($user);
    }

    public function generateRefreshToken(UsersInfoForToken $usersInfoForToken): string
    {
        $payload = [
            'email' => $usersInfoForToken->getEmail(),
            'exp' => (new \DateTimeImmutable())->modify($this->refreshTokenTtl)->getTimestamp(),
            'type' => 'refresh',
        ];

        return $this->jwtEncoder->encode($payload);
    }

    public function refreshToken(string $refreshToken): JwtTokenDto
    {
        try {
            $payload = $this->jwtEncoder->decode($refreshToken);

            if (!isset($payload['email'], $payload['exp'], $payload['type']) || 'refresh' !== $payload['type']) {
                throw new UserNotAuthenticatedException('Invalid refresh token payload.');
            }

            if ($payload['exp'] < time()) {
                throw new UserNotAuthenticatedException('Refresh token has expired.');
            }

            $user = $this->userProvider->loadUserByIdentifier($payload['email']);

            $accessToken = $this->jwtManager->create($user);
            $newRefreshToken = $this->jwtEncoder->encode([
                'email' => $payload['email'],
                'exp' => (new \DateTimeImmutable())->modify($this->refreshTokenTtl)->getTimestamp(),
                'type' => 'refresh',
            ]);

            return new JwtTokenDto($accessToken, $newRefreshToken);
        } catch (\Exception $e) {
            $this->tokenResponseSetter->clearTokens();
            throw new UserNotAuthenticatedException('Could not refresh token: '.$e->getMessage());
        }
    }

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
                    !isset($refreshPayload['exp'], $refreshPayload['email'])
                    || $refreshPayload['exp'] < time()
                    || ($refreshPayload['type'] ?? '') !== 'refresh'
                ) {
                    throw new UserNotAuthenticatedException('Refresh token invalid');
                }
                $user = $this->userProvider->loadUserByIdentifier($refreshPayload['email']);

                return new JwtTokenDto(
                    $this->jwtManager->create($user),
                    $this->generateRefreshToken(new UsersInfoForToken(
                        $user->getId(),
                        $user->getName(),
                        $user->getEmail(),
                        $user->getRoles()[0],
                    ))
                );
            } catch (\Throwable) {
                $this->tokenResponseSetter->clearTokens();
                throw new UserNotAuthenticatedException('Both tokens are invalid or expired', 400);
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeJwt(string $token): array
    {
        [$header, $payload, $signature] = explode('.', $token);

        $decoded = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
        if (!is_array($decoded)) {
            $this->tokenResponseSetter->clearTokens();
            throw new UserNotAuthenticatedException('Invalid JWT structure');
        }

        return $decoded;
    }

    public function getUserInfoFromToken(string $token): UsersInfoForToken
    {
        $payload = $this->decodeJwt($token);

        if (!isset($payload['username'])) {
            $this->tokenResponseSetter->clearTokens();
            throw new UserNotAuthenticatedException('Invalid access token.');
        }

        $result = $this->user->findByEmail($payload['username']);

        if (empty($result) || !$result instanceof Users) {
            $this->tokenResponseSetter->clearTokens();
            throw new UserNotAuthenticatedException('UserProvider returned invalid type');
        }

        $role = $result->getRoles();

        return new UsersInfoForToken(
            $result->getId(),
            $result->getName(),
            $result->getEmail(),
            $role[0],
        );
    }

    /**
     * @return string status
     */
    public function handleTokens(
        string $accessToken,
        string $refreshToken,
    ): string {
        if (empty($accessToken) || empty($refreshToken)) {
            $this->logger->error('Отсутствует токен в куках.');

            return 'invalid';
        }

        $dto = new JwtCheckDto($refreshToken, $accessToken);

        try {
            $result = $this->validateToken($dto);

            if ($result instanceof JwtTokenDto) {
                $this->tokenResponseSetter->storeTokens($result);

                return 'new_tokens';
            }

            if (true === $result) {
                return 'valid_tokens';
            }
        } catch (\Throwable $e) {
            $this->logger->error('Ошибка при обработке токенов', [
                'exception' => $e,
                'message' => $e->getMessage(),
            ]);
            $this->tokenResponseSetter->clearTokens();
            throw new UserNotAuthenticatedException('UserProvider returned invalid type');
        }

        return 'invalid';
    }
}
