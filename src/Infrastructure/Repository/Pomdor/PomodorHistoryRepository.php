<?php

namespace App\Infrastructure\Repository\Pomdor;

use App\Aplication\Dto\PomodorDto\RepPomodorDto;
use App\Aplication\Dto\PomodorDto\RepPomodoroHistory;
use App\Aplication\Dto\PomodorDto\ResPomdorCountStatistic;
use App\Domain\Entity\Habits\Habit;
use App\Domain\Entity\Pomdoro\PomodorHistory;
use App\Domain\Repository\Pomodor\PomodorHistoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PomodorHistory>
 */
class PomodorHistoryRepository extends ServiceEntityRepository implements PomodorHistoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PomodorHistory::class);
    }


    public function cretePomodorHistory(RepPomodorDto $reqPomodorDto): PomodorHistory
    {
        $history = new PomodorHistory();

        $history->setUserId($reqPomodorDto->getUserId());
        $history->setTimeFocus($reqPomodorDto->getTimeFocus());
        $history->setPeriodLabel($reqPomodorDto->getPeriodLabel());
        $focusStart = (new \DateTime())->setTimestamp($reqPomodorDto->getTimeStart());
        $focusEnd = (new \DateTime())->setTimestamp($reqPomodorDto->getTimeEnd());
        $createDate = (new \DateTime())->setTimestamp($reqPomodorDto->getCreatedDate());
        $history->setFocusStart($focusStart);
        $history->setFocusEnd($focusEnd);
        $history->setCreateDate($createDate);
        $history->setIsDelete(0);
        $em = $this->getEntityManager();
        $em->persist($history);
        $em->flush();

        return $history;
    }



    public function deletePomodorHistory(int $pomodorId): bool
    {
        $entity = $this->find($pomodorId);
        if (!$entity) {
            return false;
        }
        $entity->setIsDelete(1);
        $this->getEntityManager()->flush();
        return true;
    }




    public function getHistoryByUserId(int $userId, int $limit = 50): array
    {
        $results = $this->createQueryBuilder('h')
            ->select('h', 'hb.title AS habit_title')
            ->leftJoin('App\Domain\Entity\JunctionTabels\Habits\HabitsPomodorJunction', 'hj', 'WITH', 'hj.pomodorId = h.id')
            ->leftJoin('App\Domain\Entity\Habits\Habit', 'hb', 'WITH', 'hb.id = hj.habitsId')
            ->andWhere('h.user_id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('h.focus_start', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $data = [];
        foreach ($results as $row) {
            $history = $row[0];
            $title = $row['habit_title'] ?? null;

            $data[] = new RepPomodoroHistory(
                $history->getPeriodLabel(),
                $history->getFocusStart()->format('H:i'),
                $history->getFocusEnd()?->format('H:i'),
                $title
            );
        }

        return $data;
    }






    public function getDataByUserIdAndPeriod(int $userId, int $target): array
    {
        $now = new \DateTime();
        $start = (clone $now)->modify("-{$target} days");

        return $this->createQueryBuilder('h')
            ->andWhere('h.user_id = :userId')
            ->andWhere('h.is_delete = 0')
            ->andWhere('h.create_date BETWEEN :start AND :end')
            ->setParameter('userId', $userId)
            ->setParameter('start', $start)
            ->setParameter('end', $now)
            ->orderBy('h.create_date', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function getPomoDayByUserId(int $userId): array
    {
        $start = new \DateTime("today 00:00:00");
        $end = new \DateTime("today 23:59:59");

        return $this->createQueryBuilder('h')
            ->andWhere('h.user_id = :userId')
            ->andWhere('h.is_delete = 0')
            ->andWhere('h.create_date BETWEEN :start AND :end')
            ->setParameter('userId', $userId)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('h.create_date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getAllCountPomodorByUserId(int $userId): int|bool
    {
        try {
            return (int) $this->createQueryBuilder('h')
                ->select('COUNT(h.id)')
                ->andWhere('h.user_id = :userId')
                ->andWhere('h.is_delete = 0')
                ->setParameter('userId', $userId)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (\Exception $e) {
            return false;
        }
    }

}
