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
        $entity = new ListTasks();
        $entity->setUserId($task->user_id);
        $entity->setLabel($task->label);
        $entity->setPriority($task->priority);
        $entity->setListType($task->list_type);
        $entity->setCreatedAt(new \DateTime());
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

        if (isset($task->label)) {
            $entity->setLabel($task->label);
        }
        if (isset($task->priority)) {
            $entity->setPriority($task->priority);
        }
        if (isset($task->list_type)) {
            $entity->setListType($task->list_type);
        }
        if (isset($task->is_delete)) {
            $entity->setis_delete($task->is_delete);
        }

        $entity->setUpdatedAt(new \DateTime());
        $em->persist($entity);
        $em->flush();

        return true;
    }
}
