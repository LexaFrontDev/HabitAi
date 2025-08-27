<?php

namespace App\Infrastructure\Port;

use App\Aplication\Dto\JwtDto\JwtTokenDto;
use App\Domain\Port\RequestTokenInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestTokenStorage implements RequestTokenInterface
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    public function storeTokens(JwtTokenDto $tokens): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $request?->attributes->set('tokens', $tokens);
    }

    public function clearTokens(): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $request?->attributes->set('logout', true);
    }
}
