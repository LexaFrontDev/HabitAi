<?php

namespace App\Aplication\UseCase\TasksUseCases;

use App\Aplication\Dto\TasksDto\TasksForSaveDto;
use App\Aplication\Dto\TasksDto\TasksForUpdateDto;
use App\Domain\Repository\Tasks\TasksInterface;
use App\Domain\Service\JwtServicesInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class CommandTasksUseCase
{
    public function __construct(
        private JwtServicesInterface $jwtServices,
        private LoggerInterface $logger,
        private TasksInterface $tasksRepository,
    ) {}

    public function getUserId(Request $request): int|bool
    {
        $token = $this->jwtServices->getTokens($request);
        if (!isset($token['accessToken'])) {
            throw new \Exception('Access token is missing or invalid.');
        }
        $userId = $this->jwtServices->getUserInfoFromToken($token['accessToken'])->getUserId();
        return $userId ?? false;
    }

    public function saveTasksUseCase(TasksForSaveDto $dto, Request $request): int|bool
    {
        $userId = $this->getUserId($request);

        if (!$userId) {
            $this->logger->error('Пользователь не имеет id');
            return false;
        }

        $result = $this->tasksRepository->tasksSave($userId, $dto);
        if (!$result) {
            $this->logger->error('Сохранение не прошло', ['userId' => $userId]);
            return false;
        }

        return $result;
    }

    public function updateTasksUseCase(TasksForUpdateDto $dto, Request $request): int|bool
    {
        $userId = $this->getUserId($request);

        if (!$userId) {
            $this->logger->error('Пользователь не имеет id');
            return false;
        }

        $result = $this->tasksRepository->updateTasks($userId, $dto);
        if (!$result) {
            $this->logger->error('Обновление не прошло', ['userId' => $userId, 'taskId' => $dto->id]);
            return false;
        }

        return $result;
    }

    public function deleteTasksUseCase(int $id, Request $request): bool
    {
        $userId = $this->getUserId($request);

        if (!$userId) {
            $this->logger->error('Пользователь не имеет id');
            return false;
        }

        $result = $this->tasksRepository->deleteTasks($userId, $id);
        if (!$result) {
            $this->logger->error('Удаление не прошло', ['userId' => $userId, 'taskId' => $id]);
            return false;
        }

        return true;
    }
}
