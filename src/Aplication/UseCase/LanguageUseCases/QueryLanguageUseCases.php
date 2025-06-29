<?php

namespace App\Aplication\UseCase\LanguageUseCases;

use App\Aplication\Service\Serialaizer\PageTranslateDtoSerializer;
use App\Domain\Repository\Language\LanguageInterface;

class QueryLanguageUseCases
{
    private PageTranslateDtoSerializer $pageTranslateDtoSerializer;
    private LanguageInterface $language;

    public function __construct(
        PageTranslateDtoSerializer $pageTranslateDtoSerializer,
        LanguageInterface $language,
    ) {
        $this->pageTranslateDtoSerializer = $pageTranslateDtoSerializer;
        $this->language = $language;
    }

    public function getTranslatePageByLang(string $page, string $prefixLang)
    {
        $translate = $this->language->getPageTranslateByLangId($page, $prefixLang);
        if ($translate === null) {return null;}
        return $this->pageTranslateDtoSerializer->deserializeToDto($page, $translate['translate']);
    }
}
