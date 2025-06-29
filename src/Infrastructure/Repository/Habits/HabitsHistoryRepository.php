<?php

namespace App\Infrastructure\Repository\Habits;

use App\Aplication\Dto\HabitsDtoUseCase\GetHabitsProgress;
use App\Aplication\Dto\HabitsDtoUseCase\SaveHabitsProgress;
use App\Domain\Entity\Habits\HabitsHistory;
use App\Domain\Repository\Habits\HabitsHistoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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



}