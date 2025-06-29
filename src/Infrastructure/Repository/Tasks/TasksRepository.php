<?php

namespace App\Infrastructure\Repository\Tasks;

use App\Aplication\Dto\TasksDto\TasksDay;
use App\Aplication\Dto\TasksDto\TasksForSaveDto;
use App\Aplication\Dto\TasksDto\TasksForUpdateDto;
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
        $beginDate = !empty($date) ? new \DateTime($date) : null;
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

        $result = $this->createQueryBuilder('t')
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
            ->getQuery()
            ->getResult();

        return array_map(
            fn($task) => new TasksDay(
                id: $task->getId(),
                title: $task->getTitleTask(),
                description: $task->getDescription(),
                date: $task->getBeginDate(),
                time: $task->getTime(),
                startDate: $task->getBeginDate()
                    ? null
                    : $task->getStartDate(),
                startTime: $task->getBeginDate()
                    ? null
                    : $task->getStartTime(),
                endDate: $task->getBeginDate()
                    ? null
                    : $task->getEndDate(),
                endTime: $task->getBeginDate()
                    ? null
                    : $task->getEndTime(),
            ),
            $result
        );
    }





}
