<?php

namespace App\Infrastructure\Controller\ApiControllers\PomodorControllers;

use App\Aplication\Dto\PomodorDto\ReqPomodorDto;
use App\Aplication\UseCase\PomodorUseCases\Commands\PomodorCommandUseCase;
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





}