<?php

namespace App\Domain\Service\TwigServices;

use App\Aplication\Dto\Main\FeatureDTO;

interface FeaturesServiceInterface
{
    /**
     * @return FeatureDTO[]
     */
    public function getFeatures(): array;
}
