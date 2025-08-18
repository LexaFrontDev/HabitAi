<?php

namespace App\Infrastructure\Repository\Purpose;

use App\Aplication\Dto\PurposeDto\PurposeDto;
use App\Domain\Entity\Purpose\Purpose;
use App\Domain\Repository\Purpose\PurposeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Purpose>
 */
class PurposeRepository extends ServiceEntityRepository implements PurposeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purpose::class);
    }

    public function savePurpose(PurposeDto $purpose): int|bool
    {
        $purposeEntity = new Purpose();

        $purposeEntity->setHabitsId($purpose->getHabitsId());
        $purposeEntity->setType($purpose->getType());
        $purposeEntity->setCount($purpose->getCount());
        $purposeEntity->setCheckManually($purpose->isCheckManually());
        $purposeEntity->setautoCount($purpose->getautoCount());
        $purposeEntity->setCheckAuto($purpose->isCheckAuto());
        $purposeEntity->setCheckClose($purpose->isCheckClose());
        $em = $this->getEntityManager();
        $em->persist($purposeEntity);
        $em->flush();

        return $purposeEntity->getId() ?? false;
    }

    public function updatePurposeByHabitId(PurposeDto $dto): bool
    {
        $em = $this->getEntityManager();
        $purpose = $this->createQueryBuilder('p')
            ->where('p.habitsId = :habitsId')
            ->andWhere('p.is_delete = 0')
            ->setParameter('habitsId', $dto->getHabitsId())
            ->getQuery()
            ->getOneOrNullResult();

        if (!$purpose) {
            return false;
        }

        $purpose->setType($dto->getType());
        $purpose->setCount($dto->getCount());
        $purpose->setCheckManually($dto->isCheckManually());
        $purpose->setautoCount($dto->getautoCount());
        $purpose->setCheckAuto($dto->isCheckAuto());
        $purpose->setCheckClose($dto->isCheckClose());

        $em->flush();

        return true;
    }

    public function getPurposeCountByHabitId(int $habitId): int
    {
        return (int) $this->createQueryBuilder('h')
            ->select('h.count')
            ->where('h.habitsId = :habitId')
            ->andWhere('h.is_delete = 0')
            ->setParameter('habitId', $habitId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function deletePurposeByHabitId(int $habitId): bool
    {
        $em = $this->getEntityManager();
        $purpose = $this->createQueryBuilder('p')
            ->where('p.habitsId = :habitId')
            ->andWhere('p.is_delete = 0')
            ->setParameter('habitId', $habitId)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$purpose) {
            return false;
        }

        $em->remove($purpose);
        $em->flush();

        return true;
    }
}
