<?php

namespace App\Infrastructure\Controller\WebControllers\Main;

use App\Aplication\UseCase\PomodorUseCases\Query\QueryPomodorUseCase;
use App\Domain\Service\Tokens\AuthTokenServiceInterface;
use App\Domain\Service\TwigServices\FeaturesServiceInterface;
use App\Domain\Service\TwigServices\ReviewsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Main extends AbstractController
{
    public function __construct(
        private AuthTokenServiceInterface $authTokenService,
        private FeaturesServiceInterface $featuresService,
        private ReviewsServiceInterface $reviewsService,
        private QueryPomodorUseCase $queryPomodorUseCase,
    ) {}

    #[Route('/', name: 'main', methods: ['GET'])]
    public function register(Request $request): Response
    {
        $features = $this->featuresService->getFeatures();
        $reviews = $this->reviewsService->getReviews();
        $period = $request->query->get('period') ?? 'week';
        $stats = $this->queryPomodorUseCase->getCountAndPeriodLabel($period, $request);
        $access = $request->cookies->get('accessToken') ?? '';
        $refresh = $request->cookies->get('refreshToken') ?? '';
        $homeResponse = $this->render('TimerApp/main/home.html.twig', [
            'features' => $features,
            'reviews' => $reviews,
            'stats' => $stats ?: null,
        ]);

        $mainResponse = $this->render('TimerApp/main/main.html.twig', [
            'features' => $features,
            'reviews' => $reviews,
            'stats' => $stats ?: null,
        ]);

        $status = $this->authTokenService->handleTokens($access, $refresh, $homeResponse);
        return match ($status) {
            'new_tokens', 'valid_tokens' => $homeResponse,
            default => $mainResponse,
        };
    }
}
