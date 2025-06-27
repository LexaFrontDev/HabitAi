<?php

namespace App\Infrastructure\Controller\WebControllers\PomodorController;

use App\Aplication\UseCase\HabitsUseCase\CommandHabitsUseCase;
use App\Aplication\UseCase\HabitsUseCase\QueryHabbitsUseCase;
use App\Aplication\UseCase\PomodorUseCases\Query\QueryPomodorUseCase;
use App\Domain\Repository\Pomodor\PomodorHistoryRepositoryInterface;
use App\Infrastructure\Attribute\RequiresJwt;
use App\Infrastructure\Repository\Pomdor\PomodorHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class PomodorPageController extends AbstractController
{



    public function __construct(
        private QueryPomodorUseCase $queryPomodorUseCase,
        private CommandHabitsUseCase $commandHabitsUseCase,
        private QueryHabbitsUseCase $queryHabitsUseCase,
    ){}


    #[Route('/pomodor', name: 'pomodor_page', methods: ['GET'])]
    #[RequiresJwt]
    public function pomodorPage(Request $request): Response
    {
        $stats = $this->queryPomodorUseCase->getCountAndPeriodLabelToday($request);
        $allCount = $this->queryPomodorUseCase->getAllCountPomo($request);
        $pomoHistory = $this->queryPomodorUseCase->getPomodorHistoryByUserId($request);
        $habits = $this->queryHabitsUseCase->getHabitsForToday($request);


        return $this->render('/TimerApp/Pomodor/Page/pomodr_page.html.twig', [
            'todayPomos' => $stats['count'] ?? 0,
            'todayFocusTime' => $stats['periodLabel'] ?? '0H',
            'totalPomodorCount' => $allCount,
            'pomodorHistory' => $pomoHistory,
            'habitsList' => $habits
        ]);
    }

}