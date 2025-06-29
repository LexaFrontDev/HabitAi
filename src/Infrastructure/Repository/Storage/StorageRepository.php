<?php

namespace App\Infrastructure\Repository\Storage;

use App\Aplication\Dto\StorageDto\SaveStorageDto;
use App\Aplication\Dto\StorageDto\UpdateStorageData;
use App\Domain\Entity\Storage\Storage;
use App\Domain\Repository\Storage\StorageInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StorageRepository>
 */
class StorageRepository extends ServiceEntityRepository implements StorageInterface
{

    CONST IMAGE_TYPE = 1;
    CONST FILE_TYPE = 2;


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Storage::class);
    }

    /**
     * Сохраняет память по full path по type
     * @param SaveStorageDto $saveStorageDto
     * @return int|false
     */
    public function saveStorage(SaveStorageDto $dto): int|false
    {
        $existing = $this->createQueryBuilder('h')
            ->where('h.fullPath = :fullPath')
            ->andWhere('h.type = :type')
            ->andWhere('h.fileType = :fileType')
            ->setParameter('fullPath', $dto->fullPath)
            ->setParameter('type', $dto->type)
            ->setParameter('fileType', $dto->fileType)
            ->getQuery()
            ->getOneOrNullResult();

        if ($existing) {
            return $existing->getId();
        }

        $entity = new Storage();
        $entity->setType($dto->type);
        $entity->setFullPath($dto->fullPath);
        $entity->setFileType($dto->fileType);
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
        return $entity->getId();
    }



    /**
     * Обновляет память по идентификатору
     * @param UpdateStorageData $saveStorageDto
     * @return int|false
     */
    public function updateStorage(UpdateStorageData $dto): int|false
    {
        $storage = $this->find($dto->id);
        if (!$storage) {
            return false;
        }
        $storage->setFullPath($dto->fullPath);
        $storage->setType($dto->type);
        $storage->setFileType($dto->fileType);
        $em = $this->getEntityManager();
        $em->flush();
        return $storage->getId();
    }



    /**
     * Удаляет память по идентификатору
     * @param int $id
     * @return bool
     */
    public function deletePathById(int $id): bool
    {
        $storage = $this->find($id);

        if (!$storage) {
            return false;
        }

        $em = $this->getEntityManager();
        $em->remove($storage);
        $em->flush();

        return true;
    }




}