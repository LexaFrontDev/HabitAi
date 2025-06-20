<?php

namespace App\Infrastructure\EventListener;

use App\Aplication\Dto\JwtDto\JwtCheckDto;
use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Infrastructure\Attribute\RequiresJwt;
use App\Domain\Service\JwtServicesInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Cookie;

#[AsEventListener(event: KernelEvents::CONTROLLER)]
class JwtAuthListener
{
    private ?JwtTokenDto $newTokens = null;

    public function __construct(
        private JwtServicesInterface $jwtService
    ) {}

    public function __invoke(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if (!is_array($controller)) return;

        $reflection = new \ReflectionMethod($controller[0], $controller[1]);
        $attributes = $reflection->getAttributes(RequiresJwt::class);
        if (empty($attributes)) return;

        /** @var RequiresJwt $attribute */
        $attribute = $attributes[0]->newInstance();

        $request = $event->getRequest();
        $access = $attribute->useHeader
            ? $request->headers->get('access-token', '')
            : $request->cookies->get('accessToken', '');

        $refresh = $attribute->useHeader
            ? $request->headers->get('refresh-token', '')
            : $request->cookies->get('refreshToken', '');

        $dto = new JwtCheckDto($access, $refresh);

        try {
            $result = $this->jwtService->validateToken($dto);

            if ($result instanceof JwtTokenDto) {
                $request->attributes->set('newTokens', $result);
                $this->newTokens = $result;
            }

            if ($result === true) {
                $request->attributes->set('jwtValid', true);
            }
        } catch (\Throwable $e) {
            throw new UnauthorizedHttpException('Bearer', 'JWT is invalid or expired');
        }
    }


    #[AsEventListener(event: KernelEvents::RESPONSE)]
    public function onKernelResponse(ResponseEvent $event): void
    {
        if ($this->newTokens === null) return;

        $response = $event->getResponse();

        $accessCookie = Cookie::create(
            'accessToken',
            $this->newTokens->getAccessToken(),
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
            $this->newTokens->getRefreshToken(),
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
