<?php

namespace App\Aplication\UseCase\HabitsUseCase;

use App\Aplication\Dto\HabitsDtoUseCase\GetHabitsProgress;
use App\Aplication\Dto\HabitsDtoUseCase\GetHabitsProgressHabitsTitle;
use App\Domain\Port\TokenProviderInterface;
use App\Domain\Repository\Habits\HabitsHistoryRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use Symfony\Component\HttpFoundation\Request;

class QueryHabitsHistory
{

    public function __construct(
        private TokenProviderInterface $tokenProvider,
        private HabitsHistoryRepositoryInterface $habitsHistoryRepository,
        private JwtServicesInterface $jwtServices,
    ){}



    public function getDoneHabitsCount(): int
    {
        $token = $this->tokenProvider->getTokens();
        $userId = $this->jwtServices->getUserInfoFromToken($token->getAccessToken())->getUserId();
        return $this->habitsHistoryRepository->getCountDoneHabits($userId);
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