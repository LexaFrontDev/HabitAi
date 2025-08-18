<?php

namespace App\Infrastructure\Repository\Storage;

use App\Aplication\Dto\StorageDto\SaveStorageDto;
use App\Aplication\Dto\StorageDto\UpdateStorageData;
use App\Domain\Entity\Storage\Storage;
use App\Domain\Repository\Storage\StorageInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Storage>
 */
class StorageRepository extends ServiceEntityRepository implements StorageInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Storage::class);
    }

    /**
     * Сохраняет память по full path по type
     */
    public function saveStorage(SaveStorageDto $saveStorageDto): int|false
    {
        $existing = $this->createQueryBuilder('h')
            ->where('h.fullPath = :fullPath')
            ->andWhere('h.type = :type')
            ->andWhere('h.fileType = :fileType')
            ->andWhere('h.is_delete = 0')
            ->setParameter('fullPath', $saveStorageDto->fullPath)
            ->setParameter('type', $saveStorageDto->type)
            ->setParameter('fileType', $saveStorageDto->fileType)
            ->getQuery()
            ->getOneOrNullResult();

        if ($existing) {
            return $existing->getId();
        }

        $entity = new Storage();
        $entity->setType($saveStorageDto->type);
        $entity->setFullPath($saveStorageDto->fullPath);
        $entity->setFileType($saveStorageDto->fileType);
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();

        return $entity->getId();
    }

    /**
     * Обновляет память по идентификатору
     */
    public function updateStorage(UpdateStorageData $updateStorageDto): int|false
    {
        $storage = $this->find($updateStorageDto->id);
        if (!$storage) {
            return false;
        }
        $storage->setFullPath($updateStorageDto->fullPath);
        $storage->setType($updateStorageDto->type);
        $storage->setFileType($updateStorageDto->fileType);
        $em = $this->getEntityManager();
        $em->flush();

        return $storage->getId();
    }

    /**
     * Удаляет память по идентификатору
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

    public function fineByFileId(int $fileId): Storage|false
    {
        $storageEntity = $this->getEntityManager()->getRepository(Storage::class)->find($fileId);

        if (!$storageEntity) {
            return false;
        }

        return $storageEntity;
    }
}
