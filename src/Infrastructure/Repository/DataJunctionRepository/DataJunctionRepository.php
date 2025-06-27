<?php

namespace App\Infrastructure\Repository\DataJunctionRepository;

use App\Aplication\Dto\DatesDto\ReqDataJunction;
use App\Domain\Entity\JunctionTabels\Habits\HabitsDataJuntion;
use App\Domain\Repository\DatesRepository\DataJunctionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class DataJunctionRepository extends ServiceEntityRepository implements DataJunctionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HabitsDataJuntion::class);
    }


    public function saveDateJunction(ReqDataJunction $data): int|bool
    {
        $datesEntity = new HabitsDataJuntion();
        $datesEntity->setDataType($data->getDataType());
        $datesEntity->setHabitsId($data->getHabitsId());
        $datesEntity->setDataId($data->getDataId());
        $em = $this->getEntityManager();
        $em->persist($datesEntity);
        $em->flush();
        return $datesEntity->getId() ?? false;

    }


}