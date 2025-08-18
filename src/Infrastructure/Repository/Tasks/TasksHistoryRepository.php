<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Tasks;

use App\Domain\Entity\Tasks\TasksHistory;
use App\Domain\Repository\Tasks\TasksHistoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

// Импорт логгера

/**
 * @extends ServiceEntityRepository<TasksHistory>
 */
class TasksHistoryRepository extends ServiceEntityRepository implements TasksHistoryInterface
{
    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, TasksHistory::class);
        $this->logger = $logger;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function tasksToDoSave(int $userID, int $tasksId, ?string $monthly = null, ?string $date = null): int|bool
    {
        try {
            $entityManager = $this->getEntityManager();
            $isCheck = $this->createQueryBuilder('th')
                ->where('th.user_id = :user')
                ->andWhere('th.tasks_id = :tasks')
                ->setParameter('user', $userID)
                ->setParameter('tasks', $tasksId)
                ->getQuery()
                ->getOneOrNullResult();

            if (!empty($isCheck)) {
                $entityManager->remove($isCheck);
                $entityManager->flush();

                return true;
            }

            $entity = new TasksHistory();
            $entity->setUserId($userID);
            $entity->setTasksId($tasksId);

            if (!empty($monthly)) {
                $entity->setTimeCloseMonth($monthly);
            } elseif (!empty($date)) {
                try {
                    $beginDate = new \DateTime($date);
                    $entity->setTimeClose($beginDate);
                } catch (\Exception $e) {
                    $this->logger->error('tasksToDoSave: Некорректная дата', ['date' => $date, 'exception' => $e]);

                    return false;
                }
            }

            $entity->setCreatedAt(new \DateTime());
            $em = $this->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $entity->getId();
        } catch (\Throwable $e) {
            $this->logger->error('tasksToDoSave: Ошибка при сохранении задачи', ['exception' => $e]);

            return false;
        }
    }

    /**
     * @return bool возвращает true, если удаление прошло успешно, false если записи не найдено
     */
    public function tasksWontDo(int $userID, int $tasksId): bool
    {
        $entityManager = $this->getEntityManager();
        $record = $this->createQueryBuilder('th')
            ->where('th.user_id = :user')
            ->andWhere('th.tasksId = :tasks')
            ->setParameter('user', $userID)
            ->setParameter('tasks', $tasksId)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$record) {
            return false;
        }
        $entityManager->remove($record);
        $entityManager->flush();

        return true;
    }
}
