<?php

namespace App\Aplication\UseCase\PremiumUseCases;

use App\Aplication\Dto\PremiumDto\PremiumPlansRes;
use App\Domain\Exception\NotFoundException\NotFoundException;
use App\Domain\Service\QueriFilterInterface\FilterInterface;

class QueryPremiumUseCase
{
    public function __construct(
        private FilterInterface $filter,
    ) {
    }

    /**
     * @return PremiumPlansRes[]
     */
    public function getAllPremiumPlans(): array
    {
        $obj = $this->filter->initFilter(
            criteriasDto: null,
            tableName: 'premium_plans',
            alias: 'pp',
            select: '*'
        );
        $result = $obj->getList();

        if (empty($result)) {
            throw new NotFoundException('Не удалось получить список планов');
        }

        $premiumPlans = [];

        foreach ($result as $plan) {
            $premiumPlans[] = new PremiumPlansRes(
                name: $plan['name'] ?? '',
                desc: $plan['desc'] ?? '',
                features: isset($plan['features']) ? explode(',', $plan['features']) : [],
                highlight: (bool) ($plan['highlight'] ?? false)
            );
        }

        return $premiumPlans;
    }
}
