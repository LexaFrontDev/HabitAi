<?php

namespace App\Infrastructure\Repository\Habits;

use App\Domain\Entity\Habits\Habit;
use App\Domain\Repository\Habits\HabitsRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use App\Aplication\Dto\HabitsDto\SaveHabitDto;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @extends ServiceEntityRepository<Habit>
 */
class HabitsRepository extends ServiceEntityRepository implements HabitsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habit::class);
    }

    public function getByUserId(int $userId): Habit
    {
        return $this->createQueryBuilder('h')
            ->where('h.userId = :userId')
            ->andWhere('h.is_delete = 0')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function updateHabitById(int $habitId, int $userId, SaveHabitDto $dto): bool
    {
        $em = $this->getEntityManager();
        $habit = $this->find($habitId);

        if (!$habit) {
            throw new EntityNotFoundException('Habit not found or access denied.');
        } elseif ($habit->getUserId() !== $userId) {
            throw new AccessDeniedException('Пользователь не имеет прав');
        }

        $habit->setTitle($dto->getTitleHabit());
        $habit->setIconUrl($dto->getIconUrl());
        $habit->setQuote($dto->getQuote());
        $habit->setGoalInDays((int) $dto->getGoalInDays());
        $habit->setBeginDate((new \DateTime())->setTimestamp($dto->getBeginDate()));
        $habit->setNotificationDate($dto->getNotificationDate());

        $em->flush();

        return true;
    }

    public function deleteHabitById(int $habitId, int $userId): bool
    {
        $em = $this->getEntityManager();
        $habit = $this->find($habitId);

        if (!$habit || $habit->getUserId() !== $userId) {
            return false;
        }

        $em->remove($habit);
        $em->flush();

        return true;
    }

    public function saveHabits(SaveHabitDto $reqHabitsDto): int|bool
    {
        $habits = new Habit();
        $habits->setUserId($reqHabitsDto->getUserId());
        $habits->setTitle($reqHabitsDto->getTitleHabit());
        $habits->setIconUrl($reqHabitsDto->getIconUrl());
        $habits->setQuote($reqHabitsDto->getQuote());
        $habits->setGoalInDays((int) $reqHabitsDto->getGoalInDays());
        $beginDate = (new \DateTime())->setTimestamp($reqHabitsDto->getBeginDate());
        $habits->setBeginDate($beginDate);
        $habits->setNotificationDate($reqHabitsDto->getNotificationDate());
        $em = $this->getEntityManager();
        $em->persist($habits);
        $em->flush();

        return $habits->getId() ?? false;
    }

    /**
     * Возвращает привычки пользователя, которые активны на сегодня.
     *
     * Использует три типа расписаний:
     * - Ежедневные (daily): проверяет, что сегодня активен день недели, например 'monday' => true
     * - Еженедельные (weekly): сравнивает номер дня недели (0 — воскресенье, 6 — суббота)
     * - Повторяющиеся в месяц (repeat): проверяет, совпадает ли день месяца
     *
     * Параметры:
     *
     * @return array<int, array<string, mixed>>|false
     *
     * @throws Exception
     */
    public function getHabitsForToday(int $day, int $week, int $month, int $userId): array|bool
    {
        $dayOfWeekNumber = (int) date('w');
        $dayOfMonth = (int) date('j');

        $today = new \DateTimeImmutable('today');
        $todayStart = $today->format('Y-m-d 00:00:00');
        $todayEnd = $today->format('Y-m-d 23:59:59');
        $todayDate = $today->format('Y-m-d');

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
        FROM Habits h
        INNER JOIN habits_data_juntion hdj ON hdj.habits_id = h.id
        LEFT JOIN date_daily dd ON hdj.data_id = dd.id AND hdj.data_type = 'daily'
        LEFT JOIN date_weekly dw ON hdj.data_id = dw.id AND hdj.data_type = 'weekly'
        LEFT JOIN date_repeat_per_month dr ON hdj.data_id = dr.id AND hdj.data_type = 'repeat'
        LEFT JOIN purposes p ON p.habits_id = h.id
        LEFT JOIN habits_history hh 
               ON hh.habits_id = h.id 
              AND hh.recorded_at BETWEEN :todayStart AND :todayEnd
              AND hh.user_id = :userId
        WHERE (
            (hdj.data_type = 'daily' AND dd.`".strtolower($today->format('D'))."` = TRUE) OR
            (hdj.data_type = 'weekly' AND dw.count_days = :dayOfWeekNumber) OR
            (hdj.data_type = 'repeat' AND dr.day = :dayOfMonth)
        )
        AND h.begin_date <= :todayDate
        AND h.user_id = :userId
        AND h.is_delete = 0
    ";

        $params = [
            'dayOfWeekNumber' => $dayOfWeekNumber,
            'dayOfMonth'      => $dayOfMonth,
            'todayStart'      => $todayStart,
            'todayEnd'        => $todayEnd,
            'todayDate'       => $todayDate,
            'userId'          => $userId,
        ];


        $sqls = $sql;
        $paramss = $params;

        $result = $this->getEntityManager()
            ->getConnection()
            ->executeQuery($sql, $params)
            ->fetchAllAssociative();

        if (empty($result)) {
            return false;
        }

        return $result;
    }

    /**
     * Возвращает все привычки пользователя с пагинацией, в формате getHabitsForToday.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws Exception
     */
    public function getAllHabitsWithLimit(int $userId, int $limit = 50, int $offset = 0): array
    {
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
        FROM Habits h
        INNER JOIN habits_data_juntion hdj ON hdj.habits_id = h.id
        LEFT JOIN date_daily dd ON hdj.data_id = dd.id AND hdj.data_type = 'daily'
        LEFT JOIN date_weekly dw ON hdj.data_id = dw.id AND hdj.data_type = 'weekly'
        LEFT JOIN date_repeat_per_month dr ON hdj.data_id = dr.id AND hdj.data_type = 'repeat'
        LEFT JOIN purposes p ON p.habits_id = h.id
        LEFT JOIN habits_history hh 
               ON hh.habits_id = h.id 
              AND hh.user_id = :userId
        WHERE h.user_id = :userId
          AND h.is_delete = 0
        LIMIT :limit OFFSET :offset
    ";

        $conn = $this->getEntityManager()->getConnection();

        $params = [
            'userId' => $userId,
            'limit' => $limit,
            'offset' => $offset,
        ];

        $types = [
            'userId' => \PDO::PARAM_INT,
            'limit' => \PDO::PARAM_INT,
            'offset' => \PDO::PARAM_INT,
        ];

        return $conn->executeQuery($sql, $params, $types)->fetchAllAssociative();
    }

    /**
     * Возвращает количество привычек пользователя, которые активны на сегодня.
     *
     * Использует три типа расписаний:
     * - Ежедневные (daily): проверяет, что сегодня активен день недели, например 'monday' => true
     * - Еженедельные (weekly): сравнивает номер дня недели (0 — воскресенье, 6 — суббота)
     * - Повторяющиеся в месяц (repeat): проверяет, совпадает ли день месяца
     *
     * Параметры:
     *
     * @throws Exception
     */
    public function getCountHabitsToday(int $day, int $week, int $month, int $userId): int
    {
        $dayOfWeek = strtolower(date('D'));

        $sql = "
        SELECT COUNT(*) as count
        FROM Habits h
        INNER JOIN habits_data_juntion hdj ON hdj.habits_id = h.id
        LEFT JOIN date_daily dd ON hdj.data_id = dd.id AND hdj.data_type = 'daily'
        LEFT JOIN date_weekly dw ON hdj.data_id = dw.id AND hdj.data_type = 'weekly'
        LEFT JOIN date_repeat_per_month dr ON hdj.data_id = dr.id AND hdj.data_type = 'repeat'
        WHERE
            (dd.`{$dayOfWeek}` = TRUE OR dw.count_days = :dayOfWeekNumber OR dr.day = :dayOfMonth)
            AND h.begin_date <= :today
            AND h.user_id = :userId
            AND h.is_delete = 0
    ";

        $params = [
            'dayOfWeekNumber' => $week,
            'dayOfMonth'      => $day,
            'today'           => (new \DateTimeImmutable('today'))->format('Y-m-d'),
            'userId'          => $userId,
        ];

        return (int) $this->getEntityManager()
            ->getConnection()
            ->executeQuery($sql, $params)
            ->fetchOne();
    }
}
