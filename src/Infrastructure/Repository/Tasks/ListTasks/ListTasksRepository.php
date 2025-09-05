<?php

namespace App\Infrastructure\Repository\Tasks\ListTasks;

use App\Aplication\Dto\TasksDto\ListTasks\TasksListCreate;
use App\Aplication\Dto\TasksDto\ListTasks\TasksListUpdate;
use App\Domain\Entity\Tasks\ListTasks;
use App\Domain\Repository\Tasks\ListTasksInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListTasks>
 */
final class ListTasksRepository extends ServiceEntityRepository implements ListTasksInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListTasks::class);
    }

    public function saveListTask(TasksListCreate $task): bool
    {
        $entity = new ListTasks(
            user_id: $task->user_id,
            label: $task->label,
            priority: $task->priority,
            list_type: $task->list_type
        );
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();

        return true;
    }

    public function updateListTask(TasksListUpdate $task): bool
    {
        $em = $this->getEntityManager();
        /** @var ListTasks|null $entity */
        $entity = $this->find($task->list_id);
        if (!$entity) {
            return false;
        }
        $entity->rename($task->label);
        $entity->changePriority($task->priority);
        $entity->changeType($task->list_type);
        $entity->setUpdatedAt();
        $em->persist($entity);
        $em->flush();

        return true;
    }
}
