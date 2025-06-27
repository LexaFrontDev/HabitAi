<?php

namespace App\Infrastructure\Repository\DatesRepeatRepository;

use App\Domain\Entity\Dates\DateRepeatPerMonth;
use App\Domain\Repository\DayesRepeatRepository\DatesRepeatRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class DatesRepeatRepository extends ServiceEntityRepository implements DatesRepeatRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateRepeatPerMonth::class);
    }


    public function saveDatesRepeat(int $day): int
    {
        $repearEntity = new DateRepeatPerMonth();
        $repearEntity->setDay($day);
        $em = $this->getEntityManager();
        $em->persist($repearEntity);
        $em->flush();
        return $repearEntity->getId() ?? false;
    }


}