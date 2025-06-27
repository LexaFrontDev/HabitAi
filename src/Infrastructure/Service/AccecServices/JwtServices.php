<?php

namespace App\Infrastructure\Service\AccecServices;

use App\Aplication\Dto\JwtDto\JwtCheckDto;
use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Domain\Entity\Users;
use App\Domain\Repository\Users\UsersRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use DateTimeImmutable;

class JwtServices implements JwtServicesInterface
{
    public function __construct(
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
            $accessPayload = $this->jwtManager->parse($tokens->getAccessToken());
            if (!isset($accessPayload['exp']) || $accessPayload['exp'] < time()) {
                throw new \Exception('Access token expired');
            }
            return true;
        } catch (\Throwable $e) {
            // Если access expired, пробуем обновить через refresh
            try {
                $refreshPayload = $this->jwtManager->parse($tokens->getRefreshToken());
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
            } catch (\Throwable $e) {
                throw new AuthenticationException('Both tokens are invalid or expired');
            }
        }
    }


    public function getUserInfoFromToken(string $token): UsersInfoForToken
    {
        $payload = $this->jwtManager->parse($token);

        if(!isset($payload['username'])){
            throw new AuthenticationException('Invalid acces token.');
        }
        $result = $this->user->findByEmail($payload['username']);
        $role =  $result->getRoles();

        return new UsersInfoForToken(
            $result->getId(),
            $result->getName(),
            $result->getEmail(),
            $role[0] ?? 'user',
        );
    }

}
