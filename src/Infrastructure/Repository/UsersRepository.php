<?php

namespace App\Infrastructure\Repository;

use App\Aplication\Dto\UsersDto\UsersForUpdate;
use App\Domain\Entity\Users;
use App\Domain\Repository\Users\UsersRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Users>
 */
class UsersRepository extends ServiceEntityRepository implements UsersRepositoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Users::class);
        $this->em = $em;
    }

    public function findByEmail(string $email): Users|bool
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->andWhere('u.is_delete = false')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();



        return $user ?? false;
    }

    public function findById(int $id): Users|bool
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->andWhere('u.is_delete = false')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        return $user ?? false;
    }

    public function updateUsersInfoByEmail(string $email, UsersForUpdate $userInfo): bool
    {
        $user = $this->findByEmail($email);
        if (!$user instanceof Users) {
            return false;
        }

        if ($userInfo->getName()) {
            $user->setName($userInfo->getName());
        }

        if ($userInfo->getPassword()) {
            $user->setPassword($userInfo->getPassword());
        }

        if ($userInfo->getRole()) {
            $user->setRole($userInfo->getRole());
        }
        $this->em->flush();

        return true;
    }

    public function createUser(Users $user): int|false
    {
        try {
            $this->em->persist($user);
            $this->em->flush();

            return $user->getId();
        } catch (\Throwable $e) {
            $Throwable = $e->getMessage();

            return false;
        }
    }
}
