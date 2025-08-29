<?php

namespace App\Infrastructure\Repository\Matric;

use App\Aplication\Dto\Matric\MatricWithTasks;
use App\Aplication\Dto\Matric\ReplaceMatricColumn;
use App\Aplication\Dto\TasksDto\Tasks\TasksDateGet;
use App\Aplication\Dto\TasksDto\Tasks\TasksDay;
use App\Aplication\Dto\TasksDto\Tasks\TasksDurationGet;
use App\Domain\Entity\Matric\MatricJunction;
use App\Domain\Repository\Matric\MatricInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MatricJunction>
 */
class MatricJunctionRepository extends ServiceEntityRepository implements MatricInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatricJunction::class);
    }

    public function getMatricWithTasks(int $userId, int $tasksId): MatricWithTasks|bool
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT
           th.id AS tasksHistoryID,
            m.tasks_number,
            t.id AS task_id,
            t.title_task,
            t.description,
            t.begin_date,
            t.time,
            t.start_date,
            t.start_time,
            t.end_date,
            t.end_time
        FROM matric_column m
        JOIN task t ON t.tasks_number = m.tasks_number
        LEFT JOIN tasksHistory th ON th.tasks_id = t.id
        WHERE m.user_id = :userId
          AND m.tasks_number = :tasksId
          AND m.is_delete = 0
          AND t.is_delete = 0
    ';

        $rows = $conn->fetchAllAssociative($sql, [
            'userId' => $userId,
            'tasksId' => $tasksId,
        ]);

        if (empty($rows)) {
            return false;
        }

        $tasks = [];

        foreach ($rows as $row) {
            $tasks[] = new TasksDay(
                id: (int) $row['task_id'],
                title: $row['title_task'],
                todo: $row['tasksHistoryID'],
                timeData: new TasksDateGet(
                    date: $row['begin_date'] ? new \DateTimeImmutable($row['begin_date']) : null,
                    duration: new TasksDurationGet(
                        startDate: $row['begin_date'] ? null : $row['start_date'],
                        startTime: $row['begin_date'] ? null : $row['start_time'],
                        endDate: $row['begin_date'] ? null : $row['end_date'],
                        endTime: $row['begin_date'] ? null : $row['end_time'],
                    ),
                    time: $row['time'],
                    repeat: $row['repeat'],
                ),
                description: $row['description'],
            );
        }

        return new MatricWithTasks(
            tasksNumber: (int) $rows[0]['tasks_number'],
            tasks: $tasks
        );
    }

    public function replaceMatric(ReplaceMatricColumn $dto): bool
    {
        return false;
    }

    public function saveMatric(int $taskId, int $userId): bool
    {
        return false;
    }

    public function deleteMatric(int $taskId, int $userId): bool
    {
        return false;
    }
}
