<?php

namespace App\Aplication\Service\Serialaizer;

use Symfony\Component\Serializer\SerializerInterface;
use InvalidArgumentException;

class PageTranslateDtoSerializer
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {}

    public function deserializeToDto(string $page, array $translate): object
    {
        $dtoClass = $this->getDtoClassByPage($page);
        return $this->serializer->denormalize($translate, $dtoClass);
    }

    private function getDtoClassByPage(string $page): string
    {
        return match ($page) {
            'landing' => \App\Aplication\Dto\LangPageTranslate\LandingPageTranslateDto::class,
            'login' => \App\Aplication\Dto\LangPageTranslate\LoginPageTranslate::class,
            'premium' => \App\Aplication\Dto\LangPageTranslate\PremiumPageTranslateDto::class,
            'tasks' => \App\Aplication\Dto\LangPageTranslate\TasksTranslations::class,
            'buttons' => \App\Aplication\Dto\LangPageTranslate\ButtonsTranslations::class,
            'month' => \App\Aplication\Dto\LangPageTranslate\MonthTranslations::class,
            default => throw new InvalidArgumentException("Unknown page: $page"),
        };
    }
}
