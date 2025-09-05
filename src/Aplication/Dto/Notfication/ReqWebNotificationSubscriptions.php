<?php

namespace App\Aplication\Dto\Notfication;

class ReqWebNotificationSubscriptions
{
    /**
     * @param array<string, string> $keys
     */
    public function __construct(
        public readonly string $platform,
        public readonly string $endpoint,
        public readonly array $keys,
    ) {
    }
}
