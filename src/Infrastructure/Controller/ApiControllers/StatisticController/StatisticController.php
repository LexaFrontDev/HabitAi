<?php

namespace App\Infrastructure\Controller\ApiControllers\StatisticController;

use App\Aplication\UseCase\Statistics\StatisticsUseCase;
use App\Infrastructure\Attribute\RequiresJwt;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class StatisticController extends AbstractController
{
    public function __construct(
        private StatisticsUseCase $statisticsUseCase,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route('/api/statistic', name: 'get_statistic_by_param', methods: ['GET'])]
    #[RequiresJwt]
    public function getStatistic(Request $request): JsonResponse
    {
        $year   = $request->query->all('year');
        $label  = $request->query->get('label');
        $limit  = $request->query->getInt('limit', 50);
        $offset = $request->query->getInt('offset', 0);


        if (!empty($year) && !empty($label) && is_string($label)) {
            $result = $this->statisticsUseCase->getStaticInfoByYears(
                year: $year,
                labelStatic: $label,
                limit: $limit,
                offset: $offset
            );

            return $this->json([
                'success' => true,
                'data' => $result,
            ]);
        }

        return $this->json([
            'success' => false,
            'error' => 'Invalid parameters. Expected: year[]=int, label=string.',
        ], JsonResponse::HTTP_BAD_REQUEST);
    }
}
