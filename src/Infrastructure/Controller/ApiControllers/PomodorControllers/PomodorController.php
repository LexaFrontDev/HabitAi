<?php

namespace App\Infrastructure\Controller\ApiControllers\PomodorControllers;

use App\Aplication\Dto\PomodorDto\ReqPomodorDto;
use App\Aplication\UseCase\HabitsUseCase\CommandHabitsUseCase;
use App\Aplication\UseCase\HabitsUseCase\QueryHabbitsUseCase;
use App\Aplication\UseCase\PomodorUseCases\Commands\PomodorCommandUseCase;
use App\Aplication\UseCase\PomodorUseCases\Query\QueryPomodorUseCase;
use App\Infrastructure\Attribute\RequiresJwt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
class PomodorController extends  AbstractController
{

    public function __construct(
        private QueryPomodorUseCase $queryPomodorUseCase,
        private CommandHabitsUseCase $commandHabitsUseCase,
        private QueryHabbitsUseCase $queryHabitsUseCase,
        private PomodorCommandUseCase $commandUseCase,
    ){}


    #[Route('/api/pomodor/create', name: 'save_pomodor', methods: ['POST'])]
    #[RequiresJwt]
    public function createPomdor(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $reqPomodorDto = new ReqPomodorDto(
                (int) $data['userId'],
                (int) $data['timeFocus'],
                (int) $data['timeStart'],
                (int) $data['timeEnd'],
                (int) $data['createdDate']
            );
            $isSave = $this->commandUseCase->savePomdor($reqPomodorDto);
            if (!empty($isSave)) {
                return new JsonResponse(['success' => true], 200);
            }
            return new JsonResponse(['success' => false], 400);
        } catch (\Throwable $e) {
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }



    #[Route('/api/pomodor/summary', name: 'api_pomodor_summary', methods: ['GET'])]
    #[RequiresJwt]
    public function pomodorSummary(Request $request): JsonResponse
    {
        $stats = $this->queryPomodorUseCase->getCountAndPeriodLabelToday($request);
        $allCount = $this->queryPomodorUseCase->getAllCountPomo($request);
        $pomoHistory = $this->queryPomodorUseCase->getPomodorHistoryByUserId($request);
        $habits = $this->queryHabitsUseCase->getHabitsForToday($request);

        return $this->json([
            'todayPomos' => $stats['count'] ?? 0,
            'todayFocusTime' => $stats['periodLabel'] ?? '0H',
            'totalPomodorCount' => $allCount,
            'pomodorHistory' => $pomoHistory,
            'habitsList' => $habits
        ]);
    }


    #[Route('/api/count/period', name: 'api_pomodor_count_period', methods: ['GET'])]
    #[RequiresJwt]
    public function getPeriod(Request $request): JsonResponse
    {
        $period = $request->query->get('period') ?? 'week';
        $stats = $this->queryPomodorUseCase->getCountAndPeriodLabel($period, $request);
        return $this->json(['stats' => $stats ?: null]);
    }


}