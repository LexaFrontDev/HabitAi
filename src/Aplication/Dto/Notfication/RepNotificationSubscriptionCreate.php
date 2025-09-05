<?php

namespace App\Aplication\Dto\Notfication;

class RepNotificationSubscriptionCreate
{
    /**
     * @param array<string, string> $keys
     */
    public function __construct(
        public readonly int $userId,
        public readonly string $platform,
        public readonly string $endpoint,
        public readonly array $keys,
    ) {
    }
}
