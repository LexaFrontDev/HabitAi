<?php

namespace App\Domain\Port;

use App\Aplication\Dto\JwtDto\JwtTokenDto;

interface TokenResponseSetterInterface
{
    public function attachTokens(JwtTokenDto $tokens): void;
}
