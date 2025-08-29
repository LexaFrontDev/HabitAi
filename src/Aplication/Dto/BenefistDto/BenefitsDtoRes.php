<?php

namespace App\Aplication\Dto\BenefistDto;

final class BenefitsDtoRes
{
    public function __construct(
        public readonly string $title,
        public readonly string $desc,
        public readonly string $icon_path,
    ) {
    }



}
