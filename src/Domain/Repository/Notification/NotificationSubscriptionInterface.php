<?php

namespace App\Domain\Repository\Notification;

use App\Aplication\Dto\Notfication\RepNotificationSubscriptionCreate;

interface NotificationSubscriptionInterface
{
    public function saveNotificationSubscription(RepNotificationSubscriptionCreate $dto): bool;
}
