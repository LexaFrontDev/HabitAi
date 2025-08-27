<?php

namespace App\Infrastructure\Http;

use App\Aplication\Dto\JwtDto\JwtTokenDto;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class SymfonyTokenResponseSetter implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $request  = $event->getRequest();
        $response = $event->getResponse();


        if (true === $request->attributes->get('logout')) {
            $response->headers->clearCookie('accessToken', '/', null, false, true, Cookie::SAMESITE_STRICT);
            $response->headers->clearCookie('refreshToken', '/', null, false, true, Cookie::SAMESITE_STRICT);

            return;
        }


        $tokens = $request->attributes->get('tokens');
        if ($tokens instanceof JwtTokenDto) {
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
    }
}
