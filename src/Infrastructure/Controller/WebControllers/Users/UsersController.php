<?php

namespace App\Infrastructure\Controller\WebControllers\Users;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractController
{
    #[Route('/users/register', name: 'web_users_register', methods: ['GET'])]
    public function register(): Response
    {
        return $this->render('/TimerApp/users/AuthPages/reigster_page.html.twig');
    }

    #[Route('/users/login', name: 'web_users_login', methods: ['GET'])]
    public function login(): Response
    {
        return $this->render('/TimerApp/users/AuthPages/login_page.html.twig');
    }
}
