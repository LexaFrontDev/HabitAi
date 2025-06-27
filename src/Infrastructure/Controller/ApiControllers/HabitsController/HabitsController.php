<?php

namespace App\Infrastructure\Controller\ApiControllers\HabitsController;

use App\Aplication\Dto\HabitsDtoUseCase\ReqHabitsDto;
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
    ){}

    #[Route('/api/habits/save', name: 'save_habit', methods: ['POST'])]
    public function saveHabits(#[MapRequestPayload] ReqHabitsDto $reqHabitsDto, Request $request)
    {
        $isResult = $this->commandHabitsUseCase->saveHabits($reqHabitsDto, $request);

        if (!empty($isResult)) {
            return $this->json(['success' => true, 'data' => $isResult, 'message' => 'Привычки добавлены']);
        }

        return $this->json(['success' => false, 'message' => 'Привычки не добавлены'], 400);
    }

    #[Route('/api/get/habits/today', name: 'get_habits', methods: ['POST'])]
    public function getHabits(Request $request)
    {
        $isResult = $this->queryHabitsUseCase->getHabitsForToday($request);

        if (!empty($isResult)) {
            return $this->json(['success' => true, 'data' => $isResult]);
        }

        return $this->json(['success' => false, 'message' => 'Привычки не полученны'], 400);
    }


}
