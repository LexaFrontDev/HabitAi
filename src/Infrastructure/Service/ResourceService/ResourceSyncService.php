<?php

namespace App\Infrastructure\Service\ResourceService;

use App\Aplication\Dto\ResourceDto\BenefitsDto;
use App\Aplication\Dto\ResourceDto\FaqDto;
use App\Aplication\Dto\ResourceDto\HabitsTemplatesDto;
use App\Aplication\Dto\ResourceDto\LanguageDto;
use App\Aplication\Dto\ResourceDto\PremiumPlansDto;
use App\Domain\Entity\Habits\HabitsTemplates;
use App\Domain\Entity\Language\Language;
use App\Domain\Entity\Language\LanguagePageTranslation;
use App\Domain\Entity\Premium\Benefits;
use App\Domain\Entity\Premium\Faqs;
use App\Domain\Entity\Premium\PremiumPlans;
use App\Domain\Service\ResourceService\ResourceSyncInterface;
use Doctrine\ORM\EntityManagerInterface;

class ResourceSyncService implements ResourceSyncInterface
{
    public function __construct(
        private EntityManagerInterface $manager,
    ) {
    }

    /**
     * @param LanguageDto[] $languages
     */
    public function setLanguage(array $languages): bool
    {
        foreach ($languages as $langData) {
            $existing = $this->manager->getRepository(Language::class)
                ->findOneBy(['prefix' => $langData->prefix]);

            if ($existing) {
                continue;
            }

            $language = new Language();
            $language->setName($langData->name);
            $language->setPrefix($langData->prefix);
            $this->manager->persist($language);
        }

        $this->manager->flush();

        return true;
    }

    /**
     * @param array<string, array<string, array<string, string>>> $translations
     */
    public function setTranslation(array $translations): bool
    {
        $languages = $this->manager->getRepository(Language::class)->findAll();
        $languagesByPrefix = [];
        foreach ($languages as $lang) {
            $languagesByPrefix[$lang->getPrefix()] = $lang;
        }
        foreach ($translations as $pageName => $pageTranslations) {
            foreach ($languagesByPrefix as $prefix => $language) {
                if (!$language) {
                    continue;
                }

                $pageTranslate = $this->extractTranslationsForLanguage($pageTranslations, $prefix);

                $existing = $this->manager->getRepository(LanguagePageTranslation::class)
                    ->findOneBy([
                        'pageName' => $pageName,
                        'language' => $language,
                    ]);

                if ($existing) {
                    $existing->setPageTranslate($pageTranslate);
                } else {
                    $translation = new LanguagePageTranslation();
                    $translation->setPageName($pageName);
                    $translation->setPageTranslate($pageTranslate);
                    $translation->setLanguage($language);

                    $this->manager->persist($translation);
                }
            }
        }

        $this->manager->flush();

        return true;
    }

    /**
     * @param array<string, mixed> $translations
     *
     * @return array<string, string>
     */
    private function extractTranslationsForLanguage(array $translations, string $languageCode): array
    {
        $result = [];

        foreach ($translations as $key => $value) {
            if (is_array($value)) {
                if (isset($value[$languageCode]) && is_string($value[$languageCode])) {
                    $result[$key] = $value[$languageCode];
                } else {
                    $nested = $this->extractTranslationsForLanguage($value, $languageCode);
                    foreach ($nested as $nestedKey => $nestedValue) {
                        $result[$key.'.'.$nestedKey] = $nestedValue;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param PremiumPlansDto[] $plans
     */
    public function setPremiumPlans(array $plans): bool
    {
        foreach ($plans as $data) {
            $plan = new PremiumPlans();
            $plan->setName($data->name);
            $plan->setDesc($data->desc);
            $plan->setPrice($data->price);
            $plan->setFeatures($data->features);
            $plan->setHighlight($data->highlight);
            $this->manager->persist($plan);
        }

        $this->manager->flush();

        return true;
    }

    /**
     * @param HabitsTemplatesDto[] $templates
     */
    public function setHabitsTemplates(array $templates): bool
    {
        foreach ($templates as $data) {
            $existing = $this->manager->getRepository(HabitsTemplates::class)
                ->findOneBy(['title_template' => $data->title]);

            if ($existing) {
                continue;
            }
            $habit = new HabitsTemplates();
            $habit->setTitleTemplate($data->title);
            $habit->setQuoteTemplate($data->quote);
            $habit->setNotificationTemplate($data->notification);
            $habit->setDatesTypeTemplate($data->datesType);
            $this->manager->persist($habit);
        }

        $this->manager->flush();

        return true;
    }

    /**
     * @param FaqDto[] $faqs
     */
    public function setFaq(array $faqs): bool
    {
        foreach ($faqs as $data) {
            $faq = new Faqs();
            $faq->setQuestion($data->question);
            $faq->setAnswer($data->answer);

            $this->manager->persist($faq);
        }

        $this->manager->flush();

        return true;
    }

    /**
     * @param BenefitsDto[] $benefits
     */
    public function setBenefits(array $benefits): bool
    {
        foreach ($benefits as $feature) {
            $plan = new Benefits();
            $plan->setTitle($feature->title);
            $plan->setDesc($feature->desc);
            $plan->setIconPath($feature->icon);

            $this->manager->persist($plan);
        }

        $this->manager->flush();

        return true;
    }
}
