<?php

namespace App\Infrastructure\Controller\WebControllers\HabitsControllers;

use App\Aplication\UseCase\HabitsUseCase\CommandHabitsUseCase;
use App\Aplication\UseCase\HabitsUseCase\QueryHabbitsUseCase;
use App\Aplication\UseCase\PomodorUseCases\Query\QueryPomodorUseCase;
use App\Infrastructure\Attribute\RequiresJwt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HabitsController extends AbstractController
{
    public function __construct(
        private QueryHabbitsUseCase $queryHabitsUseCase,
    ){}
    #[Route('/habits', name: 'habits_page', methods: ['GET'])]
    #[RequiresJwt]
    public function habitsPageController(Request $request): Response
    {

        $habits = $this->queryHabitsUseCase->getHabitsForToday($request);


        return $this->render('/TimerApp/Habits/Page/habits_page.html.twig', [
            'habits' => $habits
        ]);
    }


    #[Route('/habit/chunk/steps/one', name: 'one_step', methods: ['GET'])]
    #[RequiresJwt]
    public function oneStepTwigHtml(): Response
    {
        return $this->render('/TimerApp/Habits/Chunk/steps/one_steps.html.twig');
    }

    #[Route('/habit/chunk/steps/twe_steps', name: 'twe_steps', methods: ['GET'])]
    #[RequiresJwt]
    public function twe_stepsTwigHtml(): Response
    {
        return $this->render('/TimerApp/Habits/Chunk/steps/twe_steps.html.twig');
    }

}