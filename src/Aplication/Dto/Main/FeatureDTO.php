<?php

namespace App\Aplication\Dto\Main;

class FeatureDTO
{
    public function __construct(
        public string $title,
        public string $desc,
        public string $icon,
        public string $url,
    ) {
    }
}
