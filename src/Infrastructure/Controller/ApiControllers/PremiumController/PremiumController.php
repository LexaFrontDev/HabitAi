<?php

namespace App\Infrastructure\Controller\ApiControllers\PremiumController;

use App\Aplication\UseCase\Diffrent\BenefitsUseCases\QueryBenefitsUseCases;
use App\Aplication\UseCase\Diffrent\FaqUseCase\QueryFaqUseCases;
use App\Aplication\UseCase\PremiumUseCases\QueryPremiumUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class PremiumController extends AbstractController
{
    public function __construct(
        private QueryBenefitsUseCases $queryBenefitsUseCases,
        private QueryPremiumUseCase $queryPremiumUseCase,
        private QueryFaqUseCases $queryFaqUseCases,
    ) {
    }

    #[Route('/api/get/premium/summary', name: 'get_premium_summary', methods: ['POST'])]
    public function getPremiumAllSummary(): JsonResponse
    {
        $premium = $this->queryPremiumUseCase->getAllPremiumPlans();
        $faq = $this->queryFaqUseCases->getAllFaqs();
        $benefits = $this->queryBenefitsUseCases->getAllBenefits();

        return $this->json(['premium' => $premium, 'faq' =>  $faq, 'benefits' => $benefits]);
    }
}
