<?php

namespace App\Infrastructure\Repository\Matric;

use App\Aplication\Dto\Matric\RenameColumnMatric;
use App\Domain\Entity\Matric\MatricColumn;
use App\Domain\Repository\Matric\MatricColumnInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MatricColumn>
 */
class MatricColumnRepository extends ServiceEntityRepository implements MatricColumnInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatricColumn::class);
    }

    public function renameMatricColumn(RenameColumnMatric $dto): bool
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(MatricColumn::class);

        $existing = $this->createQueryBuilder('m')
            ->where('m.userId = :userId')
            ->andWhere('m.is_delete = 0')
            ->setParameter('userId', $dto->userId)
            ->getQuery()
            ->getOneOrNullResult();

        if ($existing) {
            $existing->setFirstColumnName($dto->firstColumnName);
            $existing->setSecondColumnName($dto->secondColumnName);
            $existing->setThirdColumnName($dto->thirdColumnName);
            $existing->setFourthColumnName($dto->fourthColumnName);
        } else {
            $new = new MatricColumn();
            $new->setUserId($dto->userId);
            $new->setFirstColumnName($dto->firstColumnName);
            $new->setSecondColumnName($dto->secondColumnName);
            $new->setThirdColumnName($dto->thirdColumnName);
            $new->setFourthColumnName($dto->fourthColumnName);
            $em->persist($new);
        }

        $em->flush();

        return true;
    }
}
