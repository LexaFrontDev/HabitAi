<?php

namespace App\Domain\Service\ResourceService;

use App\Aplication\Dto\ResourceDto\BenefitsDto;
use App\Aplication\Dto\ResourceDto\FaqDto;
use App\Aplication\Dto\ResourceDto\HabitsTemplatesDto;
use App\Aplication\Dto\ResourceDto\LanguageDto;
use App\Aplication\Dto\ResourceDto\PremiumPlansDto;

interface ResourceSyncInterface
{
    /**
     * @param LanguageDto[] $languages
     */
    public function setLanguage(array $languages): bool;

    /**
     * @param array<string, array<string, array<string, string>>> $translations
     */
    public function setTranslation(string $pageName, array $translations): bool;

    /**
     * @param PremiumPlansDto[] $plans
     */
    public function setPremiumPlans(array $plans): bool;

    /**
     * @param HabitsTemplatesDto[] $templates
     */
    public function setHabitsTemplates(array $templates): bool;

    /**
     * @param FaqDto[] $faqs
     */
    public function setFaq(array $faqs): bool;

    /**
     * @param BenefitsDto[] $benefits
     */
    public function setBenefits(array $benefits): bool;
}
