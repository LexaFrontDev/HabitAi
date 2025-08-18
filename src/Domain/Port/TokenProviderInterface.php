<?php

namespace App\Domain\Port;

use App\Aplication\Dto\JwtDto\JwtTokenDto;

interface TokenProviderInterface
{
    public function getTokens(): JwtTokenDto;
}
