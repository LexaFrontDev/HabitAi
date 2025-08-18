<?php

namespace App\Infrastructure\Repository\DataJunction;

use App\Aplication\Dto\DatesDto\ReqDataJunction;
use App\Domain\Entity\JunctionTabels\Habits\HabitsDataJuntion;
use App\Domain\Exception\ExistException\ExistException;
use App\Domain\Exception\NotFoundException\NotFoundException;
use App\Domain\Repository\Dates\DataJunctionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HabitsDataJuntion>
 */
class DataJunctionRepository extends ServiceEntityRepository implements DataJunctionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HabitsDataJuntion::class);
    }

    /**
     * @throws ExistException
     */
    public function saveDateJunction(ReqDataJunction $data): int
    {
        $em = $this->getEntityManager();
        $isExist = $this->createQueryBuilder('hdj')
            ->where('hdj.habitsId = :habitsId')
            ->andWhere('hdj.data_id = :dataId')
            ->andWhere('hdj.data_type = :dataType')
            ->andWhere('hdj.is_delete = 0')
            ->setParameter('habitsId', $data->getHabitsId())
            ->setParameter('dataId', $data->getDataId())
            ->setParameter('dataType', $data->getDataType())
            ->getQuery()
            ->getOneOrNullResult();

        if ($isExist) {
            throw new ExistException('Такая связка уже существует.');
        }
        $datesEntity = new HabitsDataJuntion();
        $datesEntity->setDataType($data->getDataType());
        $datesEntity->setHabitsId($data->getHabitsId());
        $datesEntity->setDataId($data->getDataId());
        $em->persist($datesEntity);
        $em->flush();

        return $datesEntity->getId();
    }

    /**
     * @throws NotFoundException
     */
    public function updateDateJunction(int $habitsId, string $types): int|bool
    {
        $entity = $this->createQueryBuilder('hdj')
            ->where('hdj.habitsId = :habitsId')
            ->andWhere('hdj.is_delete = 0')
            ->setParameter('habitsId', $habitsId)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$entity) {
            throw new NotFoundException("Habit with ID $habitsId not found");
        }

        if ($entity->getDataType() !== $types) {
            $entity->setDataType($types);
            $entity->setUpdatedAt(new \DateTimeImmutable());
            $this->getEntityManager()->flush();

            return false;
        }

        $entity->setUpdatedAt(new \DateTimeImmutable());
        $this->getEntityManager()->flush();

        return $entity->getDataId() ?? false;
    }

    /**
     * Получить сущность связи по ID привычки
     *
     * @throws NotFoundException
     */
    public function getDateJunctionByHabitsId(int $habitsId): HabitsDataJuntion
    {
        $entity = $this->createQueryBuilder('hdj')
            ->where('hdj.habitsId = :habitsId')
            ->andWhere('hdj.is_delete = 0')
            ->setParameter('habitsId', $habitsId)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$entity) {
            throw new NotFoundException("Связь с привычкой ID $habitsId не найдена");
        }

        return $entity;
    }

    /**
     * Получить тип привычки по ID
     *
     * @throws NotFoundException
     */
    public function getTypeByHabitsId(int $habitsId): string
    {
        $entity = $this->createQueryBuilder('djc')
            ->where('djc.habitsId = :habitsId')
            ->andWhere('djc.is_delete = 0')
            ->setParameter('habitsId', $habitsId)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$entity || !$entity->getType()) {
            throw new NotFoundException('Тип привычек не найден');
        }

        return $entity->getType();
    }

    /**
     * Обновить тип привычки по ID
     *
     * @throws NotFoundException
     */
    public function updateType(int $habitsId, string $types): bool
    {
        $entity = $this->createQueryBuilder('djc')
            ->where('djc.habitsId = :habitsId')
            ->andWhere('djc.is_delete = 0')
            ->setParameter('habitsId', $habitsId)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$entity) {
            throw new NotFoundException("Привычка с ID $habitsId не найдена");
        }

        $entity->setType($types);
        $this->getEntityManager()->flush();

        return true;
    }

    /**
     * @throws ExistException
     */
    public function createJunction(int $habitsId, string $types, int $dataId): int|bool
    {
        $em = $this->getEntityManager();

        $exists = $this->createQueryBuilder('hdj')
            ->where('hdj.habitsId = :habitsId')
            ->andWhere('hdj.is_delete = 0')
            ->setParameter('habitsId', $habitsId)
            ->getQuery()
            ->getOneOrNullResult();

        if ($exists) {
            throw new ExistException("Для привычки ID $habitsId связка уже существует.");
        }

        $entity = new HabitsDataJuntion();
        $entity->setHabitsId($habitsId);
        $entity->setDataType($types);
        $entity->setDataId($dataId);

        $em->persist($entity);
        $em->flush();

        return $entity->getId();
    }

    /**
     * @throws NotFoundException
     */
    public function updateDataTypeAndId(int $id, int $habitsId, string $types, int $newDataId): int
    {
        $em = $this->getEntityManager();

        $entity = $this->find($id);

        if (empty($entity) || $entity->getHabitsId() !== $habitsId || !$entity instanceof HabitsDataJuntion) {
            throw new NotFoundException("Связь с ID $id и привычкой $habitsId не найдена.");
        }

        $entity->setDataType($types);
        $entity->setDataId($newDataId);
        $entity->setUpdatedAt(new \DateTimeImmutable());

        $em->flush();

        return $entity->getId();
    }
}
