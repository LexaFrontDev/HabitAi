<?php

namespace App\Aplication\UseCase\LanguageUseCases;

use App\Domain\Exception\Message\MessageException;
use App\Domain\Repository\Language\LanguageInterface;
use App\Aplication\Dto\LangPageTranslate\LangPageReturn;

class QueryLanguageUseCases
{
    private LanguageInterface $language;

    public function __construct(
        LanguageInterface $language,
    ) {
        $this->language = $language;
    }

    public function getTranslatePageByLang(string $version, string $prefixLang): LangPageReturn
    {
        $translate = $this->language->getPageTranslateByLangId($version, $prefixLang);
        if (empty($translate)) {
            throw new MessageException("Перевод не получено $translate");
        }

        return $translate;
    }
}
