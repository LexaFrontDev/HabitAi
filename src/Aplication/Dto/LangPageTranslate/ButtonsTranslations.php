<?php

namespace App\Aplication\Dto\LangPageTranslate;

class ButtonsTranslations
{


    public function __construct(
        public readonly string $AllButton,
        public readonly string $TodayButton,
        public readonly string $TomorowButton,
        public readonly string $WeekButton,
        public readonly string $MonthButton,
        public readonly string $addButton,
        public readonly string $EveryDay,
        public readonly string $EveryWeek,
        public readonly string $EveryMonth,
        public readonly string $EveryYear,
        public readonly string $Never,
        public readonly string $Description,
        public readonly string $ChooseTime
    ) {}

}