<?php

namespace App\Infrastructure\Controller\WebControllers\PomodorController;

use App\Infrastructure\Attribute\RequiresJwt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class PomodorPageController extends AbstractController
{


    #[Route('/pomodor', name: 'pomodor_page', methods: ['GET'])]
    #[RequiresJwt]
    public function pomodorPage(): Response
    {
        return $this->render('/TimerApp/Pomodor/Page/pomodr_page.html.twig');
    }

}