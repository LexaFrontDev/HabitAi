<?php

declare(strict_types=1);

namespace App\Domain\Repository\Language;

use App\Aplication\Dto\LangPageTranslate\LangPageReturn;

interface LanguageInterface
{
    public function getPageTranslateByLangId(string $version, string $prefix): ?LangPageReturn;
}
