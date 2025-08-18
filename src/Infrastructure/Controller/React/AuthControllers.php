<?php

namespace App\Infrastructure\Controller\React;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthControllers extends AbstractController
{
    #[Route('/Users/login', name: 'login')]
    public function home(): Response
    {
        return $this->render('base_react.html.twig');
    }

    #[Route('/Users/register', name: 'register')]
    public function register(): Response
    {
        return $this->render('base_react.html.twig');
    }
}
