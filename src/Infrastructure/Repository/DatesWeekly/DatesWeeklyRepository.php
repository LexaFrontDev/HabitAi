<?php

namespace App\Infrastructure\Repository\DatesWeekly;

use App\Domain\Entity\Dates\DateWeekly;
use App\Domain\Exception\NotFoundException\NotFoundException;
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

    public function updateDatesWeekly(int $id, int $count): bool
    {
        $em = $this->getEntityManager();
        $weeklyEntity = $this->createQueryBuilder('w')
            ->where('w.id = :id')
            ->andWhere('w.is_delete = 0')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$weeklyEntity) {
            return false;
        }

        $weeklyEntity->setCountDays($count);
        $em->flush();

        return true;
    }




    /**
     * @param int $id
     * @return bool
     * @throws NotFoundException
     */
    public function deleteDatesWeekly(int $id): bool
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository(DateWeekly::class)->find($id);

        if (!$entity) {
            throw new NotFoundException("Еженедельная дата с ID $id не найдена.");
        }

        $entity->setis_delete(true);
        $em->flush();

        return true;
    }


}