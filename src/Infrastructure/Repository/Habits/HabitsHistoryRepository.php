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
    public function saveProgress(SaveHabitsProgress $saveHabitsProgress): int|false
    {
        if (!empty($saveHabitsProgress->getId())) {
            $entity = $this->getEntityManager()->getRepository(HabitsHistory::class)->find($saveHabitsProgress->getId());

            if (!$entity) {
                return false;
            }
        } else {
            $entity = new HabitsHistory();
            $entity->setUserId($saveHabitsProgress->getUserId());
            $entity->setRecordedAt(new \DateTimeImmutable());
        }

        $entity->setCount($saveHabitsProgress->getCount());
        $entity->setCountEnd($saveHabitsProgress->getCountEnd());
        $entity->setIsDone($saveHabitsProgress->getCount() === $saveHabitsProgress->getCountEnd());

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

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