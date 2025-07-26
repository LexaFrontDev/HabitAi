<?php

namespace App\Infrastructure\Controller\ApiControllers\TasksControllers;

use App\Aplication\Dto\TasksDto\TasksForSaveDto;
use App\Aplication\Dto\TasksDto\TasksForUpdateDto;
use App\Aplication\UseCase\TasksUseCases\CommandTasksUseCase;
use App\Aplication\UseCase\TasksUseCases\QueryTasksUseCase;
use App\Infrastructure\Attribute\RequiresJwt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class TasksControllers extends AbstractController
{
    public function __construct(
        private CommandTasksUseCase $commandTasksUseCase,
        private QueryTasksUseCase $queryTasksUseCase
    ) {}

    #[Route('/api/tasks/save', name: 'tasks_save', methods: ['POST'])]
    #[RequiresJwt]
    public function save(#[MapRequestPayload] TasksForSaveDto $dto, Request $request): JsonResponse
    {
        $result = $this->commandTasksUseCase->saveTasksUseCase($dto, $request);

        if (!$result) {
            return $this->json(['success' => false, 'message' => 'Ошибка при сохранении задачи'], 400);
        }

        return $this->json(['success' => true, 'data' => $result]);
    }

    #[Route('/api/tasks/get{day}', name: 'get_tasks_by_day', methods: ['GET'])]
    #[RequiresJwt]
    public function getTasksByDay(Request $request, int $day): JsonResponse
    {
        $result = $this->queryTasksUseCase->getTasksByDay($day, $request);
        if (!empty($result)) {
            return $this->json([
                'success' => true,
                'data' => $result,
                'message' => 'Задачи успешно получены',
            ]);
        }
        return $this->json([
            'success' => false,
            'message' => 'Задачи отсутствуют',
        ]);
    }


    #[Route('/api/tasks/all/get', name: 'get_tasks_all', methods: ['GET'])]
    #[RequiresJwt]
    public function getTasksAll(Request $request): JsonResponse
    {
        $result = $this->queryTasksUseCase->getTasksAll($request);
        return $this->json($result);
    }


    #[Route('/api/tasks/update', name: 'tasks_update', methods: ['PUT'])]
    #[RequiresJwt]
    public function update(#[MapRequestPayload] TasksForUpdateDto $dto, Request $request): JsonResponse
    {
        $result = $this->commandTasksUseCase->updateTasksUseCase($dto, $request);

        if (!$result) {
            return $this->json(['success' => false, 'message' => 'Ошибка при обновлении задачи'], 400);
        }

        return $this->json(['success' => true, 'data' => $result]);
    }

    #[Route('/api/tasks/delete/{id}', name: 'tasks_delete', methods: ['DELETE'])]
    #[RequiresJwt]
    public function delete(int $id, Request $request): JsonResponse
    {
        $result = $this->commandTasksUseCase->deleteTasksUseCase($id, $request);

        if (!$result) {
            return $this->json(['success' => false, 'message' => 'Ошибка при удалении задачи'], 400);
        }

        return $this->json(['success' => true]);
    }
}
