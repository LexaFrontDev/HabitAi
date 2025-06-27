<?php

namespace App\Infrastructure\Repository\DatesWeekly;

use App\Domain\Entity\Dates\DateWeekly;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Domain\Repository\DatesWeekly\DatesWeeklyRepositoryInterface;

class DatesWeeklyRepository extends ServiceEntityRepository implements DatesWeeklyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateWeekly::class);
    }

    public function saveDatesWeekly(int $count): int|bool
    {
        $datesEntity = new DateWeekly();
        $datesEntity->setCountDays($count);
        $em = $this->getEntityManager();
        $em->persist($datesEntity);
        $em->flush();
        return $datesEntity->getId() ?? false;
    }

}