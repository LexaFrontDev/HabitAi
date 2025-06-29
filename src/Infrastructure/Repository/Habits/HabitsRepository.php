<?php

namespace App\Infrastructure\Repository\Habits;

use App\Aplication\Dto\HabitsDtoUseCase\ReqHabitsDto;
use App\Domain\Entity\Dates\DateDaily;
use App\Domain\Entity\Dates\DateRepeatPerMonth;
use App\Domain\Entity\Dates\DateWeekly;
use App\Domain\Entity\Habits\Habit;
use App\Domain\Entity\JunctionTabels\Habits\HabitsDataJuntion;
use App\Domain\Entity\Purpose\Purpose;
use App\Domain\Repository\Habits\HabitsRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Aplication\Dto\HabitsDtoUseCase\SaveHabitDto;


class HabitsRepository extends ServiceEntityRepository implements HabitsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habit::class);
    }




    public function getByUserId(int $userId)
    {
        return $this->createQueryBuilder('h')
            ->where('h.userId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery();
    }
    public function saveHabits(SaveHabitDto $reqHabitsDto): int|bool
    {
        $habits = new Habit();
        $habits->setUserId($reqHabitsDto->getUserId());
        $habits->setTitle($reqHabitsDto->getTitleHabit());
        $habits->setIconUrl($reqHabitsDto->getIconUrl());
        $habits->setQuote($reqHabitsDto->getQuote());
        $habits->setGoalInDays($reqHabitsDto->getGoalInDays());
        $beginDate = (new \DateTime())->setTimestamp($reqHabitsDto->getBeginDate());
        $habits->setBeginDate($beginDate);
        $habits->setNotificationDate($reqHabitsDto->getNotificationDate());
        $em = $this->getEntityManager();
        $em->persist($habits);
        $em->flush();
        return $habits->getId() ?? false;
    }

    public function getHabitsForToday(int $day, int $week, int $month, int $userId): array
    {
        $dayOfWeek = strtolower(date('D'));

        $sql = "
                    SELECT 
                        h.id AS habit_id,
                        h.*, 
                        p.*,
                        p.count AS count_purposes, 
                        hh.id AS habit_history_id,
                        hh.*, 
                        hdj.data_type,
                        dd.*, dw.*, dr.*
                    FROM habits h
                    INNER JOIN habits_data_juntion hdj ON hdj.habits_id = h.id
                    LEFT JOIN date_daily dd ON hdj.data_id = dd.id AND hdj.data_type = 'daily'
                    LEFT JOIN date_weekly dw ON hdj.data_id = dw.id AND hdj.data_type = 'weekly'
                    LEFT JOIN date_repeat_per_month dr ON hdj.data_id = dr.id AND hdj.data_type = 'repeat'
                    LEFT JOIN purposes p ON p.habits_id = h.id
                    LEFT JOIN habits_history hh 
                           ON hh.habits_id = h.id 
                          AND DATE(hh.recorded_at) = :today 
                          AND hh.user_id = :userId
                    WHERE
                        (dd.`{$dayOfWeek}` = TRUE OR dw.count_days = :dayOfWeekNumber OR dr.day = :dayOfMonth) 
                        AND h.begin_date <= :today
                        AND h.user_id = :userId
                    ";


        $params = [
            'dayOfWeekNumber' => $week,
            'dayOfMonth'      => $day,
            'today'           => (new \DateTimeImmutable('today'))->format('Y-m-d'),
            'userId'          => $userId,
        ];


        return $this->getEntityManager()
            ->getConnection()
            ->executeQuery($sql, $params)
            ->fetchAllAssociative();
    }









}