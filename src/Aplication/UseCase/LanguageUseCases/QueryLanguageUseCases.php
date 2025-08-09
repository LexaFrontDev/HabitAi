<?php

namespace App\Aplication\UseCase\LanguageUseCases;

use App\Aplication\Dto\LangPageTranslate\TranslateNames;
use App\Aplication\Service\Serialaizer\PageTranslateDtoSerializer;
use App\Domain\Exception\Message\MessageException;
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

    public function getTranslatePageByLang(string $version, string $prefixLang)
    {
        $translate = $this->language->getPageTranslateByLangId($version, $prefixLang);
        if ($translate === null) throw new MessageException('Перевод не получено');
        return $translate;
    }



}
