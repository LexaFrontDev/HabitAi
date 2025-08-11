<?php

namespace App\Infrastructure\Http;


use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Domain\Port\TokenResponseSetterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class SymfonyTokenResponseSetter implements TokenResponseSetterInterface, EventSubscriberInterface
{
    private ?JwtTokenDto $tokens = null;

    public function attachTokens(JwtTokenDto $tokens): void
    {
        $this->tokens = $tokens;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$this->tokens) {
            return;
        }

        $response = $event->getResponse();

        $accessCookie = Cookie::create(
            'accessToken',
            $this->tokens->getAccessToken(),
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
            $this->tokens->getRefreshToken(),
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
}
