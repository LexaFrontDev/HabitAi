<?php

namespace App\Infrastructure\Repository\DatesDailyRepository;


use App\Aplication\Dto\DatesDto\DatesDailyForSaveDto;
use App\Domain\Entity\Dates\DateDaily;
use App\Domain\Repository\DatesRepository\DatesDailyRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class DatesDailyRepository extends ServiceEntityRepository implements DatesDailyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateDaily::class);
    }




    public function saveDates(DatesDailyForSaveDto $dates): int|bool
    {
        $datesEntity = new DateDaily();

        $datesEntity->setMon($dates->isMon());
        $datesEntity->setTue($dates->isTue());
        $datesEntity->setWed($dates->isWed());
        $datesEntity->setThu($dates->isThu());
        $datesEntity->setFri($dates->isFri());
        $datesEntity->setSat($dates->isSat());
        $datesEntity->setSun($dates->isSun());

        $em = $this->getEntityManager();
        $em->persist($datesEntity);
        $em->flush();

        return $datesEntity->getId() ?? false;
    }




}