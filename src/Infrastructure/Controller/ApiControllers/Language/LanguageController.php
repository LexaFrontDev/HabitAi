<?php

namespace App\Infrastructure\Controller\ApiControllers\Language;

use App\Aplication\Dto\LangPageTranslate\TranslateNames;
use App\Aplication\UseCase\LanguageUseCases\QueryLanguageUseCases;
use App\Domain\Repository\Language\LanguagePrefixInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class LanguageController extends AbstractController
{
    public function __construct(
        private QueryLanguageUseCases $queryLanguageUseCases,
        private LanguagePrefixInterface $languagePrefix,
    ) {}

        #[Route('/api/language', name: 'get_page_language', methods: ['GET'])]
        public function getPageLanguage(Request $request): JsonResponse {
            $versions = $request->query->get('versions');
            $lang = $request->query->get('lang');
            if (!$versions || !$lang) return $this->json(['error' => 'Перевод или версия пустая'], 400);
            $dto = $this->queryLanguageUseCases->getTranslatePageByLang($versions, $lang);
            if ($dto === null) return $this->json(['error' => 'Translation not found'], 404);
            return $this->json($dto);
        }


    #[Route('/api/get/prefixs', name: 'get_language_prefixs', methods: ['GET'])]
    public function getLangPreixs(Request $request): JsonResponse {
        return $this->json(['result' => $this->languagePrefix->getPrefixes()]);
    }

}
