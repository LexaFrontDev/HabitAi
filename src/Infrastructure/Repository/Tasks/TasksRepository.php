<?php

namespace App\Infrastructure\Repository\Tasks;

use App\Aplication\Dto\TasksDto\Tasks\TasksDateGet;
use App\Aplication\Dto\TasksDto\Tasks\TasksDay;
use App\Aplication\Dto\TasksDto\Tasks\TasksDurationGet;
use App\Aplication\Dto\TasksDto\Tasks\TasksForSaveDto;
use App\Aplication\Dto\TasksDto\Tasks\TasksForUpdateDto;
use App\Domain\Entity\Tasks\Task;
use App\Domain\Repository\Tasks\TasksInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TasksRepository extends ServiceEntityRepository implements TasksInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function tasksSave(int $userId, TasksForSaveDto $tasksForSaveDto): int|bool
    {
        $tasksEntity = new Task();
        $tasksEntity->setUserId($userId);
        $tasksEntity->setTitleTask($tasksForSaveDto->title);

        $beginDate = !empty($tasksForSaveDto->timeData->date)
            ? new \DateTime($tasksForSaveDto->timeData->date)
            : null;

        $tasksEntity->setBeginDate($beginDate);
        $tasksEntity->setDescription($tasksForSaveDto->description);
        $tasksEntity->setTime($tasksForSaveDto->timeData->time);
        $tasksEntity->setRepeatMode($tasksForSaveDto->timeData->repeat);
        $tasksEntity->setStartDate($tasksForSaveDto->timeData->duration->startDate);
        $tasksEntity->setStartTime($tasksForSaveDto->timeData->duration->startTime);
        $tasksEntity->setEndDate($tasksForSaveDto->timeData->duration->endDate);
        $tasksEntity->setEndTime($tasksForSaveDto->timeData->duration->endTime);

        $em = $this->getEntityManager();
        $em->persist($tasksEntity);
        $em->flush();

        return $tasksEntity->getId();
    }

    public function updateTasks(int $userId, TasksForUpdateDto $tasksForUpdateDto): int|bool
    {
        $em = $this->getEntityManager();
        $task = $this->find($tasksForUpdateDto->id);

        if (!$task || $task->getUserId() !== $userId) {
            return false;
        }

        $task->setTitleTask($tasksForUpdateDto->title);

        $beginDate = !empty($tasksForUpdateDto->timeData->date)
            ? new \DateTime($tasksForUpdateDto->timeData->date)
            : null;

        $task->setBeginDate($beginDate);
        $task->setTime($tasksForUpdateDto->timeData->time);
        $task->setRepeatMode($tasksForUpdateDto->timeData->repeat);
        $task->setDescription($tasksForUpdateDto->description);
        $task->setStartDate($tasksForUpdateDto->timeData->duration->startDate);
        $task->setStartTime($tasksForUpdateDto->timeData->duration->startTime);
        $task->setEndDate($tasksForUpdateDto->timeData->duration->endDate);
        $task->setEndTime($tasksForUpdateDto->timeData->duration->endTime);

        $em->flush();

        return $task->getId();
    }

    public function deleteTasks(int $userId, int $id): bool
    {
        $em = $this->getEntityManager();
        $task = $this->find($id);

        if (!$task || $task->getUserId() !== $userId) {
            return false;
        }

        $em->remove($task);
        $em->flush();

        return true;
    }

    /**
     * Возвращает массив DTO задач пользователя за указанный день.
     *
     * @param int $day Ymd, например 20250817
     *
     * @return TasksDay[]
     */
    public function getTasksByDay(int $userId, int $day): array
    {
        $fullDate = \DateTime::createFromFormat('Ymd', (string) $day);
        if (false === $fullDate) {
            return [];
        }

        $formattedDay = $fullDate->format('m/d');

        $qb = $this->createQueryBuilder('t')
            ->leftJoin('App\Domain\Entity\Tasks\TasksHistory', 'th', 'WITH', 'th.tasks_id = t.id')
            ->where('t.userId = :userId')
            ->andWhere('t.is_delete = 0')
            ->andWhere('(
                (t.beginDate IS NOT NULL AND t.beginDate <= :fullDate)
                OR (
                    t.beginDate IS NULL AND
                    t.startDate <= :formattedDay AND
                    t.endDate >= :formattedDay
                )
            )')
            ->setParameter('userId', $userId)
            ->setParameter('fullDate', $fullDate)
            ->setParameter('formattedDay', $formattedDay)
            ->select('t', 'th.id AS historyId');

        /** @var array<int, array{0: Task, historyId: int|null}> $result */
        $result = $qb->getQuery()->getResult();

        return array_map(
            /**
             * @param array{0: Task, historyId: int|null} $row
             */
            function (array $row): TasksDay {
                $task = $row[0];
                $hasHistory = isset($row['historyId']);

                return new TasksDay(
                    id: $task->getId(),
                    title: $task->getTitleTask(),
                    todo: $hasHistory,
                    timeData: new TasksDateGet(
                        date: $task->getBeginDate() ?: null,
                        duration: new TasksDurationGet(
                            startDate: $task->getBeginDate() ? null : $task->getStartDate(),
                            startTime: $task->getBeginDate() ? null : $task->getStartTime(),
                            endDate: $task->getBeginDate() ? null : $task->getEndDate(),
                            endTime: $task->getBeginDate() ? null : $task->getEndTime(),
                        ),
                        time: $task->getTime(),
                        repeat: $task->getRepeatMode() ?? 'none',
                    ),
                    description: $task->getDescription(),
                );
            },
            $result
        );
    }

    /**
     * @return TasksDay[]
     */
    public function getTasksAllByUserId(int $userId): array
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t', 'th.id AS historyId')
            ->leftJoin('App\Domain\Entity\Tasks\TasksHistory', 'th', 'WITH', 'th.tasks_id = t.id')
            ->where('t.userId = :userId')
            ->andWhere('t.is_delete = 0')
            ->setParameter('userId', $userId);

        /** @var array<int, array{0: array<string, mixed>, historyId?: int|null}> $result */
        $result = $qb->getQuery()->getArrayResult();

        return array_map(
            /**
             * @param array{0: array<string, mixed>, historyId?: int|null} $row
             */
            function (array $row): TasksDay {
                $t = $row[0];
                $beginDate = $t['beginDate'] ?? null;

                $hasHistory = isset($row['historyId']);

                return new TasksDay(
                    id: (int) $t['id'],
                    title: (string) $t['titleTask'],
                    todo: $hasHistory,
                    timeData: new TasksDateGet(
                        date: $beginDate,
                        duration: new TasksDurationGet(
                            startDate: $beginDate ? null : ($t['startDate'] ?? null),
                            startTime: $beginDate ? null : ($t['startTime'] ?? null),
                            endDate: $beginDate ? null : ($t['endDate'] ?? null),
                            endTime: $beginDate ? null : ($t['endTime'] ?? null),
                        ),
                        time: $t['time'] ?? null,
                        repeat: $t['repeatMode'] ?? 'none',
                    ),
                    description: $t['description'] ?? null,
                );
            },
            $result
        );
    }
}
