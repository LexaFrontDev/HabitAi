<?php

namespace App\Infrastructure\Repository\DatesDaily;


use App\Aplication\Dto\DatesDto\DatesDailyForSaveDto;
use App\Domain\Entity\Dates\DateDaily;
use App\Domain\Exception\ExistException\ExistException;
use App\Domain\Exception\NotFoundException\NotFoundException;
use App\Domain\Repository\Dates\DatesDailyRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class DatesDailyRepository extends ServiceEntityRepository implements DatesDailyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateDaily::class);
    }




    /**
     * @param DatesDailyForSaveDto $dates
     * @return int
     */
    public function saveDates(DatesDailyForSaveDto $dates): int
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
        return $datesEntity->getId();
    }

    public function updateDates(int $id, DatesDailyForSaveDto $dates): bool
    {
        $em = $this->getEntityManager();
        $datesEntity = $this->createQueryBuilder('d')
            ->where('d.id = :id')
            ->andWhere('d.is_delete = 0')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$datesEntity) {
            return false;
        }

        $datesEntity->setMon($dates->isMon());
        $datesEntity->setTue($dates->isTue());
        $datesEntity->setWed($dates->isWed());
        $datesEntity->setThu($dates->isThu());
        $datesEntity->setFri($dates->isFri());
        $datesEntity->setSat($dates->isSat());
        $datesEntity->setSun($dates->isSun());

        $em->flush();

        return true;
    }

    /**
     * @param int $id
     * @return bool
     * @throws NotFoundException
     */
    public function deleteDatesDaily(int $id): bool
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository(DateDaily::class)->find($id);

        if (!$entity) {
            throw new NotFoundException("Еженедельная дата с ID $id не найдена.");
        }

        $entity->setis_delete(true);
        $em->flush();

        return true;
    }

}