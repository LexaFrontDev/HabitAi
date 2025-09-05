<?php

namespace App\Infrastructure\Controller\ApiControllers\TasksControllers;

use App\Aplication\Dto\TasksDto\ListTasks\TasksListReq;
use App\Aplication\Dto\TasksDto\ListTasks\TasksListReqWithListId;
use App\Aplication\Dto\TasksDto\Tasks\TasksForSaveDto;
use App\Aplication\Dto\TasksDto\Tasks\TasksForUpdateDto;
use App\Aplication\UseCase\TasksUseCases\ListTasks\CommandListTasks;
use App\Aplication\UseCase\TasksUseCases\ListTasks\QueryListTasks;
use App\Aplication\UseCase\TasksUseCases\Tasks\CommandTasksUseCase;
use App\Aplication\UseCase\TasksUseCases\Tasks\QueryTasksUseCase;
use App\Aplication\UseCase\TasksUseCases\TasksHistory\TasksHistoryUseCases;
use App\Infrastructure\Attribute\RequiresJwt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class TasksControllers extends AbstractController
{
    public function __construct(
        private CommandTasksUseCase $commandTasksUseCase,
        private CommandListTasks $commandListTasks,
        private QueryTasksUseCase $queryTasksUseCase,
        private TasksHistoryUseCases $tasksHistoryUseCases,
        private QueryListTasks $queryListTasks,
    ) {
    }

    #[Route('/api/tasks/save', name: 'tasks_save', methods: ['POST'])]
    #[RequiresJwt]
    public function save(#[MapRequestPayload] TasksForSaveDto $dto): JsonResponse
    {
        $result = $this->commandTasksUseCase->saveTasksUseCase($dto);

        if (!$result) {
            return $this->json(['success' => false, 'message' => 'Ошибка при сохранении задачи'], 400);
        }

        return $this->json(['success' => true, 'data' => $result]);
    }

    #[Route('/api/tasks/get{day}', name: 'get_tasks_by_day', methods: ['GET'])]
    #[RequiresJwt]
    public function getTasksByDay(int $day): JsonResponse
    {
        $result = $this->queryTasksUseCase->getTasksByDay($day);
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
    public function getTasksAll(): JsonResponse
    {
        $result = $this->queryTasksUseCase->getTasksAll();

        return $this->json($result);
    }

    #[Route('/api/tasks/update', name: 'tasks_update', methods: ['PUT'])]
    #[RequiresJwt]
    public function update(#[MapRequestPayload] TasksForUpdateDto $dto): JsonResponse
    {
        $result = $this->commandTasksUseCase->updateTasksUseCase($dto);

        if (!$result) {
            return $this->json(['success' => false, 'message' => 'Ошибка при обновлении задачи'], 400);
        }

        return $this->json(['success' => true, 'data' => $result]);
    }

    #[Route('/api/list/tasks/save', name: 'list_tasks_save', methods: ['POST'])]
    #[RequiresJwt]
    public function saveListTasks(#[MapRequestPayload] TasksListReq $dto): JsonResponse
    {
        $result = $this->commandListTasks->saveListTasks($dto);

        if (!$result) {
            return $this->json(['success' => false, 'message' => 'Ошибка при сохранение списка задачи'], 400);
        }

        return $this->json(['success' => true, 'data' => $result]);
    }

    #[Route('/api/list/tasks/update', name: 'list_tasks_update', methods: ['PUT'])]
    #[RequiresJwt]
    public function updateListTasks(#[MapRequestPayload] TasksListReqWithListId $dto): JsonResponse
    {
        $result = $this->commandListTasks->updateListTasks($dto);

        if (!$result) {
            return $this->json(['success' => false, 'message' => 'Ошибка при обновлении списка задачи'], 400);
        }

        return $this->json(['success' => true, 'data' => $result]);
    }

    #[Route('/api/tasks/delete/{id}', name: 'tasks_delete', methods: ['DELETE'])]
    #[RequiresJwt]
    public function delete(int $id): JsonResponse
    {
        $result = $this->commandTasksUseCase->deleteTasksUseCase($id);

        if (!$result) {
            return $this->json(['success' => false, 'message' => 'Ошибка при удалении задачи'], 400);
        }

        return $this->json(['success' => true]);
    }

    #[Route('/api/tasks/to/do', name: 'tasks_to_do', methods: ['POST'])]
    #[RequiresJwt]
    public function tasksToDoSave(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $taskId = isset($data['task_id']) ? $data['task_id'] : null;
        $month = isset($data['month']) ? $data['month'] : null;
        $date = isset($data['date']) ? $data['date'] : null;
        if (empty($taskId)) {
            return $this->json(['success' => false, 'error' => 'Task id  имеет не корректный тип или название не верное как передавать название task_id тип int'], 400);
        }
        $result = $this->tasksHistoryUseCases->saveToDo($taskId, $month, $date);

        return $this->json(['success' => true, 'data' => $result]);
    }

    #[Route('/api/list/tasks/all', name: 'get_lists_tasks_all', methods: ['GET'])]
    #[RequiresJwt]
    public function getListTasksAll(): JsonResponse
    {
        return $this->json(['success' => true, 'result' => $this->queryListTasks->getAllList()]);
    }
}
