<?php

namespace App\Infrastructure\Controller\ApiControllers\ChunkControllers;

use App\Infrastructure\Service\TwigServices\SidebarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SidebarController extends AbstractController
{
    public function __construct(
        private SidebarService $sidebarService,
    ) {
    }

    #[Route('/api/sidebar', name: 'sidebar_list', methods: ['GET'])]
    public function sideBarUlList(): Response
    {
        $data = $this->sidebarService->getItems();

        $response = $this->json($data);
        $response->setPublic();
        $response->setMaxAge(3600);
        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }
}
