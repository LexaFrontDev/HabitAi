<?php

namespace App\Domain\Service\TwigServices;

use App\Aplication\Dto\Main\ReviewsDto;

interface ReviewsServiceInterface
{
    /**
     * @return ReviewsDto[]
     */
    public function getReviews(): array;
}
