<?php

namespace App\Aplication\UseCase\Service\JwtTokens;

use App\Aplication\Dto\JwtDto\JwtCheckDto;
use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Domain\Exception\UsersException\UserNotAuthenticatedException;
use App\Domain\Service\JwtServicesInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class JwtUseCase
{
    public function __construct(
        private JwtServicesInterface $jwtServices,
    ) {
    }

    public function checkJwtToken(JwtCheckDto $jwtCheckDto): JwtTokenDto|bool
    {
        try {
            $result = $this->jwtServices->validateToken($jwtCheckDto);
            if ($result instanceof JwtTokenDto) {
                return $result;
            }

            if (empty($result)) {
                throw new UserNotAuthenticatedException('Вы не авторизованны');
            } else {
                return true;
            }
        } catch (AuthenticationException $e) {
            return false;
        }
    }
}
