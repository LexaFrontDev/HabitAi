<?php

namespace App\Infrastructure\EventListener;

use App\Aplication\Dto\JwtDto\JwtCheckDto;
use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Aplication\UseCase\Service\JwtTokens\JwtUseCase;
use App\Domain\Exception\UsersException\UserNotAuthenticatedException;
use App\Domain\Port\TokenProviderInterface;
use App\Infrastructure\Attribute\RequiresJwt;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: KernelEvents::CONTROLLER)]
class JwtAuthListener
{
    private ?JwtTokenDto $newTokens = null;

    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private JwtUseCase $jwtUseCase,
    ) {
    }

    /**
     * @throws \ReflectionException
     */
    public function __invoke(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            [$controllerObject, $method] = $controller;
            $reflection = new \ReflectionMethod($controllerObject, $method);

            $attributes = $reflection->getAttributes(RequiresJwt::class);

            if (empty($attributes)) {
                return;
            }
        } else {
            return;
        }


        $token = $this->tokenProvider->getTokens();
        $dto = new JwtCheckDto(
            refreshToken: $token->getRefreshToken(),
            accessToken: $token->getAccessToken()
        );

        $isCheck = $this->jwtUseCase->checkJwtToken($dto);
        $request = $event->getRequest();

        if ($isCheck instanceof JwtTokenDto) {
            $this->newTokens = $isCheck;
            $request->attributes->set('newTokens', $isCheck);
        } elseif (true === $isCheck) {
            $request->attributes->set('jwtValid', true);
        } else {
            throw new UserNotAuthenticatedException('Пользователь не авторизован');
        }
    }

    #[AsEventListener(event: KernelEvents::RESPONSE)]
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$this->newTokens) {
            return;
        }

        $dto = new JwtTokenDto(
            accessToken: $this->newTokens->getAccessToken(),
            refreshToken: $this->newTokens->getRefreshToken()
        );
        $request = $event->getRequest();
        $request->attributes->set('tokens', $dto);
    }
}
