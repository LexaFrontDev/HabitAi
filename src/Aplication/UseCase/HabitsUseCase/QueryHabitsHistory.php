<?php

namespace App\Aplication\UseCase\HabitsUseCase;

use App\Aplication\Dto\HabitsDto\GetHabitsProgress;
use App\Aplication\Dto\HabitsDto\GetHabitsProgressHabitsTitle;
use App\Domain\Exception\Message\MessageException;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Habits\HabitsHistoryRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;

class QueryHabitsHistory
{
    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private HabitsHistoryRepositoryInterface $habitsHistoryRepository,
        private JwtServicesInterface $jwtServices,
    ) {
    }

    public function getDoneHabitsCount(): int
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();
        $result = $this->habitsHistoryRepository->getCountDoneHabits($userId);
        if (empty($result) || !is_int($result)) {
            throw new MessageException('Не удалось получить историю привычек');
        }

        return $result;
    }

    /**
     * @return GetHabitsProgress[]|false
     * */
    public function getAllProgressHabits(): array|false
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();

        return $this->habitsHistoryRepository->getAllProgress($userId);
    }

    /**
     * @return GetHabitsProgressHabitsTitle[]|false
     * */
    public function getAllProgressWithHabitsTitle(): array|false
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();

        return $this->habitsHistoryRepository->getAllProgressWithHabitsTitle($userId);
    }
}
