<?php

namespace App\Aplication\Dto\LangPageTranslate;

class LangPageReturn
{
    /**
     * @param array<string, array<string, string>> $translate
     */
    public function __construct(
        public readonly array $translate,
        public readonly string $prefix,
    ) {
    }
}
