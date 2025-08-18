<?php

namespace App\Aplication\Dto\LangPageTranslate;

class PremiumPageTranslateDto
{
    public function __construct(
        public readonly string $goToTasksButtonText,
        public readonly string $loginButtonText,
        public readonly string $registerButtonText,
        public readonly string $HeadText,
        public readonly string $DescText,
        public readonly string $plansTitle,
        public readonly string $plansSubTitle,
        public readonly string $benefitsTitle,
        public readonly string $readyQuestion,
        public readonly string $faqTitle,
        public readonly string $readyButton,
        public readonly string $selectPlanText,
    ) {
    }
}
