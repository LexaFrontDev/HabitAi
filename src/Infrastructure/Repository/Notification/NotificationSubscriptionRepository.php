<?php

namespace App\Infrastructure\Repository\Notification;

use App\Aplication\Dto\Notfication\RepNotificationSubscriptionCreate;
use App\Domain\Entity\Notification\UserPushSubscriptions;
use App\Domain\Repository\Notification\NotificationSubscriptionInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserPushSubscriptions>
 */
class NotificationSubscriptionRepository extends ServiceEntityRepository implements NotificationSubscriptionInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPushSubscriptions::class);
    }

    public function saveNotificationSubscription(RepNotificationSubscriptionCreate $dto): bool
    {
        $em = $this->getEntityManager();
        $existing = $this->createQueryBuilder('uns')
            ->where('uns.user_id = :userId')
            ->andWhere('uns.platform = :platform')
            ->setParameter('userId', $dto->userId)
            ->setParameter('platform', $dto->platform)
            ->getQuery()
            ->getOneOrNullResult();

        if ($existing) {
            $existing->updateEndPoint($dto->endpoint, $dto->keys);
            $em->flush();

            return true;
        }

        $entity = new UserPushSubscriptions(
            userId: $dto->userId,
            platform: $dto->platform,
            endpoint: $dto->endpoint,
            keys: $dto->keys,
        );

        $em->persist($entity);
        $em->flush();

        return true;
    }
}
