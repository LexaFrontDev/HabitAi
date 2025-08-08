<?php

namespace App\Infrastructure\Repository\Tasks;

use App\Aplication\Dto\TasksDto\TasksDateGet;
use App\Aplication\Dto\TasksDto\TasksDay;
use App\Aplication\Dto\TasksDto\TasksDurationGet;
use App\Aplication\Dto\TasksDto\TasksForSaveDto;
use App\Aplication\Dto\TasksDto\TasksForUpdateDto;
use App\Domain\Entity\Tasks\Task;
use App\Domain\Entity\Tasks\TasksHistory;
use App\Domain\Repository\Tasks\TasksInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Aplication\Dto\TasksDto\TasksDateDto;
use App\Aplication\Dto\TasksDto\TaskDurationDto;

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
     * Возвращает массив DTO задач пользователя за указанный день
     * @param int $userId ID пользователя
     * @param int $day День в формате Ymd или timestamp
     * @return TasksDay[] Массив задач за день
     */
    public function getTasksByDay(int $userId, int $day): array
    {
        $fullDate = \DateTime::createFromFormat('Ymd', (string)$day);
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
            ->select('t', 'th.id AS historyId')
        ;

        $result = $qb->getQuery()->getResult();

        return array_map(
            fn($row) => new TasksDay(
                id: $row[0]->getId(),
                title: $row[0]->getTitleTask(),
                todo: isset($row['historyId']) && $row['historyId'] !== null,
                description: $row[0]->getDescription(),
                timeData: new TasksDateGet(
                    date: $row[0]->getBeginDate() ?? null,
                    time: $row[0]->getTime(),
                    repeat: $row[0]->getRepeatMode() ?? 'none',
                    duration: new TasksDurationGet(
                        startDate: $row[0]->getBeginDate() ? null : $row[0]->getStartDate(),
                        startTime: $row[0]->getBeginDate() ? null : $row[0]->getStartTime(),
                        endDate: $row[0]->getBeginDate() ? null : $row[0]->getEndDate(),
                        endTime: $row[0]->getBeginDate() ? null : $row[0]->getEndTime(),
                    )
                )
            ),
            $result
        );
    }


    /***
     * @param int $userId
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

        $result = $qb->getQuery()->getArrayResult();

        return array_map(
            fn($row) => new TasksDay(
                id: $row[0]['id'],
                title: $row[0]['titleTask'],
                todo: isset($row['historyId']) && $row['historyId'] !== null,
                description: $row[0]['description'],
                timeData: new TasksDateGet(
                    date: $row[0]['beginDate'] ?? null,
                    time: $row[0]['time'] ?? null,
                    repeat: $row[0]['repeatMode'] ?? 'none',
                    duration: new TasksDurationGet(
                        startDate: ($row[0]['beginDate'] ?? null) ? null : $row[0]['startDate'] ?? null,
                        startTime: ($row[0]['beginDate'] ?? null) ? null : $row[0]['startTime'] ?? null,
                        endDate: ($row[0]['beginDate'] ?? null) ? null : $row[0]['endDate'] ?? null,
                        endTime: ($row[0]['beginDate'] ?? null) ? null : $row[0]['endTime'] ?? null,
                    )
                )
            ),
            $result
        );
    }




}
