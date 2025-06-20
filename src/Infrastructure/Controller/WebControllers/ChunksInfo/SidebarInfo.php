<?php

namespace App\Infrastructure\Controller\WebControllers\ChunksInfo;

use App\Infrastructure\Attribute\RequiresJwt;
use App\Infrastructure\Service\TwigServices\SidebarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
class SidebarInfo extends AbstractController
{


    public function __construct(
        private SidebarService $sidebarService
    ){}


    #[Route('/web/sidebar', name: 'sidebar_list', methods: ['GET'])]
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