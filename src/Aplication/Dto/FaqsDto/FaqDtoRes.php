<?php

namespace App\Aplication\Dto\FaqsDto;

final class FaqDtoRes
{
    public function __construct(
        public readonly string $question,
        public readonly string $answer,
    ) {
    }


}
