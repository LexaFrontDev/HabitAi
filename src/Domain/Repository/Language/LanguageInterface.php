<?php

declare(strict_types=1);

namespace App\Domain\Repository\Language;

interface LanguageInterface
{


    /**
     * @param string $page
     * @param string $prefix
     * @return array{translate: array, lang: string}|null
     */
    public function getPageTranslateByLangId(string $page, string $prefix): ?array;
}