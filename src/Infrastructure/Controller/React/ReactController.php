<?php

namespace App\Infrastructure\Controller\React;

use App\Infrastructure\Attribute\RequiresJwt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReactController extends AbstractController
{


    #[Route('/{reactRouting}', name: 'react_app', requirements: ['reactRouting' => '^(?!api|users/login).*'])]
    #[RequiresJwt]
    public function index(): Response
    {
        return $this->render('base_react.html.twig');
    }
}