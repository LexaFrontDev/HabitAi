<?php

namespace App\Aplication\UseCase\TasksUseCases;

use App\Aplication\Dto\TasksDto\TasksForSaveDto;
use App\Aplication\Dto\TasksDto\TasksForUpdateDto;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Tasks\TasksInterface;
use App\Domain\Service\JwtServicesInterface;
use Psr\Log\LoggerInterface;

class CommandTasksUseCase
{
    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private JwtServicesInterface $jwtServices,
        private LoggerInterface $logger,
        private TasksInterface $tasksRepository,
    ) {
    }

    public function getUserId(): int|bool
    {
        $token = $this->tokenProvider->getTokens();

        return $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();
    }

    public function saveTasksUseCase(TasksForSaveDto $dto): int|bool
    {
        $userId = $this->getUserId();

        if (!$userId || !is_int($userId)) {
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

    public function updateTasksUseCase(TasksForUpdateDto $dto): int|bool
    {
        $userId = $this->getUserId();

        if (!$userId || !is_int($userId)) {
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

    public function deleteTasksUseCase(int $id): bool
    {
        $userId = $this->getUserId();

        if (!$userId || !is_int($userId)) {
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
