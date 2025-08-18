<?php

namespace App\Infrastructure\Controller\ApiControllers\MainController;

use App\Domain\Service\TwigServices\FeaturesServiceInterface;
use App\Domain\Service\TwigServices\ReviewsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiLandingController extends AbstractController
{
    public function __construct(
        private FeaturesServiceInterface $featuresService,
        private ReviewsServiceInterface $reviewsService,
    ) {
    }

    #[Route('/api/landing/data', name: 'get_landing_data', methods: ['get'])]
    public function lendingData(): JsonResponse
    {
        $features = $this->featuresService->getFeatures();
        $reviews = $this->reviewsService->getReviews();

        return $this->json(['success' => true, 'features' => $features, 'reviews' => $reviews]);
    }
}
