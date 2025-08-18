<?php

namespace App\Infrastructure\EventListener;

use App\Aplication\Dto\JwtDto\JwtCheckDto;
use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Infrastructure\Attribute\RequiresJwt;
use App\Domain\Service\JwtServicesInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Request;

#[AsEventListener(event: KernelEvents::CONTROLLER)]
class JwtAuthListener
{
    private ?JwtTokenDto $newTokens = null;

    public function __construct(private JwtServicesInterface $jwtService)
    {
    }

    public function __invoke(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if (!is_array($controller) || 2 !== count($controller) || !is_object($controller[0]) || !is_string($controller[1])) {
            return;
        }

        $attribute = $this->getRequiresJwtAttribute($controller);
        if (!$attribute) {
            return;
        }

        $request = $event->getRequest();
        $this->checkTokens($request, $attribute->useHeader);
    }

    #[AsEventListener(event: KernelEvents::RESPONSE)]
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$this->newTokens) {
            return;
        }

        $this->setAuthCookies($event);
    }

    /**
     * @param array{0: object, 1: string} $controller
     *
     * @throws \ReflectionException
     */
    private function getRequiresJwtAttribute(array $controller): ?RequiresJwt
    {

        $reflection = new \ReflectionMethod($controller[0], $controller[1]);
        $attributes = $reflection->getAttributes(RequiresJwt::class);

        return $attributes ? $attributes[0]->newInstance() : null;
    }

    private function checkTokens(Request $request, bool $useHeader): void
    {
        $access = $request->cookies->get('accessToken') ?? $request->headers->get('access-token');
        $refresh = $request->cookies->get('refreshToken') ?? $request->headers->get('refresh-token');

        $dto = new JwtCheckDto($access ?? '', $refresh ?? '');

        try {
            $result = $this->jwtService->validateToken($dto);

            if ($result instanceof JwtTokenDto) {
                $this->newTokens = $result;
                $request->attributes->set('newTokens', $result);
            } elseif (true === $result) {
                $request->attributes->set('jwtValid', true);
            }
        } catch (\Throwable) {
            $this->handleAuthError($request);
        }
    }

    private function handleAuthError(Request $request): void
    {
        if ($this->isApiRequest($request)) {
            throw new UnauthorizedHttpException('Bearer', 'JWT is invalid or expired');
        }

        $currentPath = $request->getPathInfo();
        if ('/Users/login' === $currentPath) {
            return;
        }

        $response = new RedirectResponse('/Users/login');
        $response->send();
        exit;
    }

    private function isApiRequest(Request $request): bool
    {
        return str_contains($request->headers->get('accept', ''), 'application/json')
            || str_starts_with($request->getPathInfo(), '/api');
    }

    private function setAuthCookies(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        $response->headers->setCookie(Cookie::create(
            'accessToken',
            $this->newTokens->getAccessToken(),
            strtotime('+7 days'),
            '/',
            null,
            false,
            true,
            false,
            Cookie::SAMESITE_STRICT
        ));

        $response->headers->setCookie(Cookie::create(
            'refreshToken',
            $this->newTokens->getRefreshToken(),
            strtotime('+7 days'),
            '/',
            null,
            false,
            true,
            false,
            Cookie::SAMESITE_STRICT
        ));
    }
}
