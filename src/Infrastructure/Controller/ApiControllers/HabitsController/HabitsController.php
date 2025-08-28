<?php

namespace App\Infrastructure\Controller\ApiControllers\HabitsController;

use App\Aplication\Dto\HabitsDto\ReqHabitsDto;
use App\Aplication\Dto\HabitsDto\ReqUpdateHabitsDto;
use App\Aplication\Dto\HabitsDto\SaveHabitsProgress;
use App\Aplication\UseCase\HabitsUseCase\Habits\CommandHabitsUseCase;
use App\Aplication\UseCase\HabitsUseCase\Habits\QueryHabbitsUseCase;
use App\Aplication\UseCase\HabitsUseCase\HabitsHistory\CommandHabitsHistoryUseCase;
use App\Aplication\UseCase\HabitsUseCase\HabitsHistory\QueryHabitsHistory;
use App\Aplication\UseCase\HabitsUseCase\HabitsTemplates\QueryHabitsTemplates;
use App\Infrastructure\Attribute\RequiresJwt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class HabitsController extends AbstractController
{
    public function __construct(
        private CommandHabitsUseCase $commandHabitsUseCase,
        private QueryHabbitsUseCase $queryHabitsUseCase,
        private QueryHabitsHistory $queryHabitsHistory,
        private CommandHabitsHistoryUseCase $commandHabitsHistoryUseCase,
        private QueryHabitsTemplates $queryHabitsTemplates,
    ) {
    }

    #[Route('/api/Habits/save', name: 'save_habit', methods: ['POST'])]
    #[RequiresJwt]
    public function saveHabits(#[MapRequestPayload] ReqHabitsDto $reqHabitsDto): JsonResponse
    {
        $isResult = $this->commandHabitsUseCase->saveHabits($reqHabitsDto);

        if (!empty($isResult)) {
            return $this->json(['success' => true, 'data' => $isResult, 'message' => 'Привычки добавлены']);
        }

        return $this->json(['success' => false, 'message' => 'Привычки не добавлены'], 400);
    }

    #[Route('/api/Habits/update', name: 'update_habit', methods: ['PUT'])]
    #[RequiresJwt]
    public function updateHabits(#[MapRequestPayload] ReqUpdateHabitsDto $reqHabitsDto): JsonResponse
    {
        $isResult = $this->commandHabitsUseCase->updateHabits($reqHabitsDto);

        if (!empty($isResult)) {
            return $this->json(['success' => true, 'data' => $isResult, 'message' => 'Привычки обновлены']);
        }

        return $this->json(['success' => false, 'message' => 'Привычки не обновлены'], 400);
    }

    #[Route('/api/get/Habits/by/day', name: 'get_habits', methods: ['GET'])]
    #[RequiresJwt]
    public function getHabits(Request $request): JsonResponse
    {
        $date = $request->query->get('date');
        $dateTime = \DateTime::createFromFormat('Y-m-d', $date);
        if (!$dateTime || $dateTime->format('Y-m-d') !== $date) {
            return $this->json([
                'success' => false,
                'message' => 'Неверный формат даты. Ожидается YYYY-MM-DD',
            ], 400);
        }

        $habits = $this->queryHabitsUseCase->getHabitsForDate($date);
        if (!empty($habits)) {
            return $this->json(['success' => true, 'data' => $habits]);
        }

        return $this->json([
            'success' => false,
            'message' => 'Привычки не получены',
        ], 404);
    }

    #[Route('/api/get/Habits/all', name: 'get_habits_all', methods: ['GET'])]
    #[RequiresJwt]
    public function getHabitsAll(Request $request): JsonResponse
    {
        $limit = (int) $request->query->get('limit', null) ?: 50;
        $offset = (int) $request->query->get('offset', null) ?: 0;


        if ($limit < 1 || $offset < 0) {
            return $this->json([
                'success' => false,
                'message' => 'Параметры limit и offset должны быть положительными числами',
                'code' => 400,
            ], 400);
        }

        if ($limit > 150) {
            return $this->json([
                'success' => false,
                'message' => 'Больше 150 привычек получать нельзя',
                'code' => 400,
            ], 400);
        }
        $result = $this->queryHabitsUseCase->getHabitsWidthLimit($limit, $offset);

        return $this->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    #[Route('/api/get/count/Habits/today', name: 'get_count_habits', methods: ['GET'])]
    #[RequiresJwt]
    public function getHabitsContToDay(): JsonResponse
    {
        $countHabits = $this->queryHabitsUseCase->getCountHabitsToDay();
        $countDoneHabits = $this->queryHabitsHistory->getDoneHabitsCount();
        $progressHabits = $this->queryHabitsHistory->getAllProgressWithHabitsTitle();

        return $this->json([
            'success' => true,
            'data' => [
                'count_habits' => $countHabits,
                'count_done_habits' => $countDoneHabits,
                'progress_habits' => $progressHabits,
            ],
        ]);
    }

    #[Route('/api/Habits/save/progress', name: 'save_habit_progress', methods: ['POST'])]
    #[RequiresJwt]
    public function saveHabitsProgress(#[MapRequestPayload] SaveHabitsProgress $reqHabitsDto): JsonResponse
    {
        $isResult = $this->commandHabitsHistoryUseCase->saveHabitsProgress($reqHabitsDto);

        if (isset($isResult['success']) && true === $isResult['success']) {
            return $this->json(['success' => true, 'data' => $isResult, 'message' => 'Прогресс добавлен']);
        }

        return $this->json(['success' => false, 'message' => 'Прогресс не добавлены'], 400);
    }

    #[Route('/api/Habits/delete/{habitId}', name: 'delete_habit', methods: ['DELETE'])]
    #[RequiresJwt]
    public function deleteHabits(int $habitId): JsonResponse
    {
        $isResult = $this->commandHabitsUseCase->deleteHabitsById($habitId);
        if (empty($isResult)) {
            return $this->json(['success' => false, 'data' => 'Не удалось удалить привычку']);
        }

        return $this->json(['success' => true, 'message' => 'Привычка удаленно'], 200);
    }

    #[Route('/api/Habits/statistic/all', name: 'get_habit_statistic_all', methods: ['GET'])]
    #[RequiresJwt]
    public function getHabitsStatisticAll(): JsonResponse
    {
        return $this->json(['success' => true, 'result' => $this->queryHabitsHistory->getAllProgressWithHabitsTitleAll()]);
    }

    #[Route('/api/Habits/templates/all', name: 'get_habit_templates_all', methods: ['GET'])]
    #[RequiresJwt]
    public function getHabitsTemplatesAll(): JsonResponse
    {
        return $this->json(['success' => true, 'result' => $this->queryHabitsTemplates->getAllTemplates()]);
    }

    #[Route('/api/Habits/statistic/all/{habitsId}', name: 'get_habit_statistic', methods: ['GET'])]
    #[RequiresJwt]
    public function getHabitsStatisticAllByHabitId(int $habitsId): JsonResponse
    {
        return $this->json(['success' => true, 'result' => $this->queryHabitsHistory->getAllProgressWithHabitsTitleByHabitsId($habitsId)]);
    }
}
