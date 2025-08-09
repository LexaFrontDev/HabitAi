<?php

namespace App\Aplication\Dto\LangPageTranslate;

class LangPageReturn
{
    public function __construct(
        public readonly array $translate,
        public readonly string $prefix
    ){}
}