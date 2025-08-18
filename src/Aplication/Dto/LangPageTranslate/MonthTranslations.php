<?php

namespace App\Aplication\Dto\LangPageTranslate;

class MonthTranslations
{
    public function __construct(
        public readonly string $January,
        public readonly string $February,
        public readonly string $March,
        public readonly string $April,
        public readonly string $May,
        public readonly string $June,
        public readonly string $July,
        public readonly string $August,
        public readonly string $September,
        public readonly string $October,
        public readonly string $November,
        public readonly string $December,
    ) {
    }
}
