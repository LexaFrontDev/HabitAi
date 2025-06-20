<?php

namespace App\Infrastructure\Service\TwigServices;

use App\Aplication\Dto\Main\ReviewsDto;
use App\Domain\Service\TwigServices\ReviewsServiceInterface;

class ReviewsService implements ReviewsServiceInterface
{
    /**
     * @return ReviewsDto[]
     */
    public function getReviews(): array
    {
        return [
            new ReviewsDto(
                name: 'Алина',
                comment: 'TaskFlow помог мне начать утро без прокрастинации.'
            ),
            new ReviewsDto(
                name: 'Рустам',
                comment: 'Люблю ваш таймер — сосредоточиться стало проще!'
            ),
            new ReviewsDto(
                name: 'Медина',
                comment: 'Простой, чистый дизайн. Всё понятно с первого клика.'
            ),
        ];
    }
}
