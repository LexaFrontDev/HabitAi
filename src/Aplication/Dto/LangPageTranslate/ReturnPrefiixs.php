<?php

namespace App\Aplication\Dto\LangPageTranslate;

class ReturnPrefiixs
{
    public function __construct(
        public readonly string $lang_label,
        public readonly string $prefix,
    ) {
    }

}
