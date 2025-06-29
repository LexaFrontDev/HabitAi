<?php

namespace App\Aplication\UseCase\HabitsUseCase;

use App\Aplication\Dto\HabitsDtoUseCase\GetHabitsProgress;
use App\Aplication\Dto\HabitsDtoUseCase\GetHabitsProgressHabitsTitle;
use App\Domain\Repository\Habits\HabitsHistoryRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use Symfony\Component\HttpFoundation\Request;

class QueryHabitsHistory
{

    public function __construct(
        private HabitsHistoryRepositoryInterface $habitsHistoryRepository,
        private JwtServicesInterface $jwtServices,
    ){}



    public function getDoneHabitsCount(Request $request): int
    {
        $token = $this->jwtServices->getTokens($request);
        $userId = $this->jwtServices->getUserInfoFromToken($token['accessToken'])->getUserId();
        return $this->habitsHistoryRepository->getCountDoneHabits($userId);
    }


    /**
     * @param Request $request
     * @return GetHabitsProgress[]|false
     * */
    public function getAllProgressHabits(Request $request): array|false
    {
        $token = $this->jwtServices->getTokens($request);
        $userId = $this->jwtServices->getUserInfoFromToken($token['accessToken'])->getUserId();
        return $this->habitsHistoryRepository->getAllProgress($userId);
    }


    /**
     * @param Request $request
     * @return GetHabitsProgressHabitsTitle[]|false
     * */
    public function getAllProgressWithHabitsTitle(Request $request): array|false
    {
        $token = $this->jwtServices->getTokens($request);
        $userId = $this->jwtServices->getUserInfoFromToken($token['accessToken'])->getUserId();
        return $this->habitsHistoryRepository->getAllProgressWithHabitsTitle($userId);
    }

}