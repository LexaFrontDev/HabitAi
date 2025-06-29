<?php

namespace App\Infrastructure\Repository\Habits;

use App\Aplication\Dto\HabitsDtoUseCase\GetHabitsProgress;
use App\Aplication\Dto\HabitsDtoUseCase\GetHabitsProgressHabitsTitle;
use App\Aplication\Dto\HabitsDtoUseCase\SaveHabitsProgress;
use App\Domain\Entity\Habits\HabitsHistory;
use App\Domain\Repository\Habits\HabitsHistoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;


class HabitsHistoryRepository extends ServiceEntityRepository implements HabitsHistoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HabitsHistory::class);
    }



    /**
     * Сохраняеть прогресс привычек или обновляеть в зависимости от id в @SaveHabitsProgress
     * @return int|bool
     */
    public function saveProgress(SaveHabitsProgress $saveHabitsProgress, int $countPurposes): int|false
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(HabitsHistory::class);

        $todayStart = (new \DateTimeImmutable('today'))->setTime(0, 0);
        $todayEnd = $todayStart->setTime(23, 59, 59);
        $entity = $repo->createQueryBuilder('h')
            ->where('h.habits_id = :habitId')
            ->andWhere('h.userId = :userId')
            ->andWhere('h.recordedAt BETWEEN :start AND :end')
            ->setParameter('habitId', $saveHabitsProgress->getHabitsId())
            ->setParameter('userId', $saveHabitsProgress->getUserId())
            ->setParameter('start', $todayStart)
            ->setParameter('end', $todayEnd)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$entity) {
            $entity = new HabitsHistory();
            $entity->setHabitsId($saveHabitsProgress->getHabitsId());
            $entity->setUserId($saveHabitsProgress->getUserId());
            $entity->setRecordedAt(new \DateTimeImmutable());
        }
        $entity->setCount($countPurposes);
        $entity->setCountEnd($saveHabitsProgress->getCountEnd());
        $entity->setIsDone($countPurposes === $saveHabitsProgress->getCountEnd());
        $em->persist($entity);
        $em->flush();
        return $entity->getId();
    }





    public function getProgressToday($userId): array|false
    {

    }



    /**
     * Возвращает весь прогресс для статистики
     *
     * @return GetHabitsProgress[]|false
     */
    public function getAllProgress(int $userId): array|false
    {
        $qb = $this->createQueryBuilder('h')
            ->where('h.userId = :userId')
            ->andWhere('h.is_delete = 0')
            ->setParameter('userId', $userId)
            ->orderBy('h.recordedAt', 'ASC');
        $results = $qb->getQuery()->getResult();
        if (empty($results)) return false;
        return array_map(fn(HabitsHistory $item) => new GetHabitsProgress(
            $item->getId(),
            $item->getHabitsId(),
            $item->getCount(),
            $item->getCountEnd(),
            $item->getIsDone(),
            $item->getRecordedAt()
        ), $results);
    }


    /**
     * Возвращает весь прогресс с названием привычек для статистики
     *
     * @param int $userId
     * @return GetHabitsProgressHabitsTitle[]|false
     * @throws Exception
     * @throws \DateMalformedStringException
     */
    public function getAllProgressWithHabitsTitle(int $userId): array|false
    {
        $sql = "
                SELECT 
                    hh.id,
                    hh.habits_id,
                    hh.count,
                    hh.count_end,
                    hh.is_done,
                    hh.recorded_at,
                    h.title
                FROM habits_history hh
                INNER JOIN habits h ON h.id = hh.habits_id
                WHERE hh.user_id = :userId
                AND hh.is_delete = 0
                AND h.is_delete = 0
                ORDER BY hh.recorded_at ASC";

        $results = $this->getEntityManager()
            ->getConnection()
            ->executeQuery($sql, ['userId' => $userId])
            ->fetchAllAssociative();
        if (empty($results)) {
            return false;
        }
        return array_map(fn(array $row) => new GetHabitsProgressHabitsTitle(
            (int) $row['id'],
            $row['title'],
            (int) $row['habits_id'],
            (int) $row['count'],
            (int) $row['count_end'],
            (bool) $row['is_done'],
            new \DateTimeImmutable($row['recorded_at'])
        ), $results);
    }




    /**
     * Получает количество выполненных задач
     * @param int $userId
     * @return int
     */
    public function getCountDoneHabits(int $userId): int
    {
        return (int) $this->createQueryBuilder('h')
            ->select('COUNT(h.id)')
            ->where('h.userId = :userId')
            ->andWhere('h.isDone = 1')
            ->andWhere('h.is_delete = 0')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }




}