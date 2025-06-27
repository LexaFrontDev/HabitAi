<?php

namespace App\Infrastructure\Repository\PurposeRepository;

use App\Aplication\Dto\PurposeDto\PurposeDto;
use App\Domain\Entity\Purpose\Purpose;
use App\Domain\Repository\PurposeRepository\PurposeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


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



}