<?php

namespace App\Domain\Port;

use App\Aplication\Dto\JwtDto\JwtTokenDto;

interface RequestTokenInterface
{
    public function storeTokens(JwtTokenDto $tokens): void;

    public function clearTokens(): void;
}
