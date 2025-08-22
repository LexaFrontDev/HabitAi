<?php

namespace App\Aplication\UseCase\TasksUseCases;

use App\Aplication\Dto\TasksDto\TasksDay;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Tasks\TasksInterface;
use App\Domain\Service\JwtServicesInterface;
use Psr\Log\LoggerInterface;

class QueryTasksUseCase
{
    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private JwtServicesInterface $jwtServices,
        private TasksInterface $tasksRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @return TasksDay[]|bool Массив задач за день
     */
    public function getTasksByDay(int $day): array|bool
    {
        $token = $this->tokenProvider->getTokens();
        $user = $this->jwtServices->getUserInfoFromToken($token->getAccessToken());
        $userId = $user->getUserId();

        try {
            $result = $this->tasksRepository->getTasksByDay($userId, $day);
            if (!empty($result)) {
                return $result;
            }

            return false;
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());

            return false;
        }
    }

    /**
     * @return TasksDay[]
     */
    public function getTasksAll(): array
    {
        $token = $this->tokenProvider->getTokens();
        $user = $this->jwtServices->getUserInfoFromToken($token->getAccessToken());
        $userId = $user->getUserId();

        $isResult = $this->tasksRepository->getTasksAllByUserId($userId);

        if (!empty($isResult)) {
            return $isResult;
        }

        return [];
    }
}
