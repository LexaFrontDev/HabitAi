<?php

namespace App\Domain\Repository\Language;

use App\Aplication\Dto\LangPageTranslate\ReturnPrefiixs;

interface LanguagePrefixInterface
{
    /**
     * @return ReturnPrefiixs[]
     */
    public function getPrefixes(): array;
}
