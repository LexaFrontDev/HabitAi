<?php

namespace App\Aplication\Dto\LangPageTranslate;

class TasksTranslations
{
    public function __construct(
        public readonly string $headText,
        public readonly string $wantToDo,
    ) {
    }
}
