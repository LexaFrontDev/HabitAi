<?php

declare(strict_types=1);

namespace App\Domain\Repository\Language;

use App\Aplication\Dto\LangPageTranslate\LangPageReturn;
use App\Aplication\Dto\LangPageTranslate\TranslateNames;

interface LanguageInterface
{

    /**
     * @param string $version
     * @param string $prefix
     * @return LangPageReturn|null
     */
    public function getPageTranslateByLangId(string $version, string $prefix): ?LangPageReturn;

}