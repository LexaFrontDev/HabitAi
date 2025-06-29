<?php

namespace App\Infrastructure\Controller\ApiControllers\Language;

use App\Aplication\UseCase\LanguageUseCases\QueryLanguageUseCases;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class LanguageController extends AbstractController
{
    public function __construct(
        private QueryLanguageUseCases $queryLanguageUseCases,
    ) {}


        #[Route('/api/language', name: 'get_page_language', methods: ['GET'])]
        public function getPageLanguage(Request $request): JsonResponse {
            $page = $request->query->get('page');
            $lang = $request->query->get('lang');

            if (!$page || !$lang) {
                return $this->json(['error' => 'Missing parameters: page or lang'], 400);
            }

            $dto = $this->queryLanguageUseCases->getTranslatePageByLang($page, $lang);

            if ($dto === null) {
                return $this->json(['error' => 'Translation not found'], 404);
            }

            return $this->json(get_object_vars($dto));
        }

        #[Route('/api/landing/language/{prefix}', name: 'get_landing_language', methods: ['GET'])]
        public function queryLanguage(string $prefix): JsonResponse
        {
            $dto = $this->queryLanguageUseCases->getTranslatePageByLang('landing', $prefix);

            if ($dto === null) {
                return $this->json(['error' => 'Translation not found'], 404);
            }
            dump($dto);
            return $this->json(get_object_vars($dto));
        }
}
