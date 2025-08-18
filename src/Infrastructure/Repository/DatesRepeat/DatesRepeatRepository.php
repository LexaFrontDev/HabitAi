<?php

namespace App\Infrastructure\Repository\DatesRepeat;

use App\Domain\Entity\Dates\DateRepeatPerMonth;
use App\Domain\Exception\NotFoundException\NotFoundException;
use App\Domain\Repository\DayesRepeat\DatesRepeatRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DateRepeatPerMonth>
 */
class DatesRepeatRepository extends ServiceEntityRepository implements DatesRepeatRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateRepeatPerMonth::class);
    }

    public function saveDatesRepeat(int $day): int|bool
    {
        $repearEntity = new DateRepeatPerMonth();
        $repearEntity->setDay($day);
        $em = $this->getEntityManager();
        $em->persist($repearEntity);
        $em->flush();

        return $repearEntity->getId() ?? false;
    }

    public function updateDatesRepeat(int $id, int $day): bool
    {
        $em = $this->getEntityManager();
        $repeatEntity = $this->createQueryBuilder('r')
            ->where('r.id = :id')
            ->andWhere('r.is_delete = 0')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$repeatEntity) {
            return false;
        }

        $repeatEntity->setDay($day);
        $em->flush();

        return true;
    }

    /**
     * @throws NotFoundException
     */
    public function deleteDatesRepeat(int $id): bool
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository(DateRepeatPerMonth::class)->find($id);

        if (!$entity) {
            throw new NotFoundException("Еженедельная дата с ID $id не найдена.");
        }

        $entity->setis_delete(true);
        $em->flush();

        return true;
    }
}
