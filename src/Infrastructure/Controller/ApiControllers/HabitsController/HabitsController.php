<?php

namespace App\Infrastructure\Controller\ApiControllers\HabitsController;

use App\Aplication\Dto\HabitsDtoUseCase\ReqHabitsDto;
use App\Aplication\Dto\HabitsDtoUseCase\SaveHabitsProgress;
use App\Aplication\UseCase\HabitsUseCase\CommandHabitsHistoryUseCase;
use App\Aplication\UseCase\HabitsUseCase\CommandHabitsUseCase;
use App\Aplication\UseCase\HabitsUseCase\QueryHabbitsUseCase;
use App\Infrastructure\Attribute\RequiresJwt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class HabitsController extends AbstractController
{
    public function __construct(
        private CommandHabitsUseCase $commandHabitsUseCase,
        private QueryHabbitsUseCase $queryHabitsUseCase,
        private CommandHabitsHistoryUseCase  $commandHabitsHistoryUseCase,
    ){}

    #[Route('/api/habits/save', name: 'save_habit', methods: ['POST'])]
    #[RequiresJwt]
    public function saveHabits(#[MapRequestPayload] ReqHabitsDto $reqHabitsDto, Request $request)
    {
        $isResult = $this->commandHabitsUseCase->saveHabits($reqHabitsDto, $request);

        if (!empty($isResult)) {
            return $this->json(['success' => true, 'data' => $isResult, 'message' => 'Привычки добавлены']);
        }

        return $this->json(['success' => false, 'message' => 'Привычки не добавлены'], 400);
    }

    #[Route('/api/get/habits/today', name: 'get_habits', methods: ['POST'])]
    #[RequiresJwt]
    public function getHabits(Request $request)
    {
        $isResult = $this->queryHabitsUseCase->getHabitsForToday($request);

        if (!empty($isResult)) {
            return $this->json(['success' => true, 'data' => $isResult]);
        }

        return $this->json(['success' => false, 'message' => 'Привычки не полученны'], 400);
    }



    #[Route('/api/habits/save/progress', name: 'save_habit_progress', methods: ['POST'])]
    #[RequiresJwt]
    public function saveHabitsProgress(#[MapRequestPayload] SaveHabitsProgress $reqHabitsDto, Request $request)
    {
        $isResult = $this->commandHabitsHistoryUseCase->saveHabitsProgress($reqHabitsDto, $request);

        if (isset($isResult['success']) && $isResult['success'] === true) {
            return $this->json(['success' => true, 'data' => $isResult, 'message' => 'Прогресс добавлен']);
        }

        return $this->json(['success' => false, 'message' => 'Прогресс не добавлены'], 400);
    }

}
