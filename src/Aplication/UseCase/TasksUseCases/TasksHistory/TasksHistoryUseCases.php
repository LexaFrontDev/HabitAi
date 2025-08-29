<?php

namespace App\Aplication\UseCase\TasksUseCases\TasksHistory;

use App\Domain\Exception\Message\MessageException;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Tasks\TasksHistoryInterface;
use App\Domain\Service\JwtServicesInterface;

class TasksHistoryUseCases
{
    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private JwtServicesInterface $jwtServices,
        private TasksHistoryInterface $tasksHistoryInterface,
    ) {
    }

    public function saveToDo(int $tasksId, ?string $monthly = null, ?string $date = null): int
    {
        $token = $this->tokenProvider->getTokens();
        $user = $this->jwtServices->getUserInfoFromToken($token->getAccessToken());
        $userId = $user->getUserId();
        $result = $this->tasksHistoryInterface->tasksToDoSave($userId, $tasksId, $monthly, $date);

        if (empty($result) || !is_int($result)) {
            throw new MessageException('Не удалось сохранить выполнение задачи');
        }

        return $result;
    }

    public function deleteToDo(int $tasksId): bool
    {
        $token = $this->tokenProvider->getTokens();
        $user = $this->jwtServices->getUserInfoFromToken($token->getAccessToken());
        $userId = $user->getUserId();
        $isDelete = $this->tasksHistoryInterface->tasksWontDo($tasksId, $userId);
        if (empty($isDelete)) {
            throw new MessageException('Не удалось удалить выполнение задачи');
        }

        return true;
    }
}
