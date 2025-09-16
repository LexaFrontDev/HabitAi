<?php

namespace App\Aplication\Dto\ResourceDto;

final class BenefitsDto
{
    /**
     * @param string $title Заголовок преимущества
     * @param string $desc Описание преимущества
     * @param string $icon Путь к иконке
     */
    public function __construct(
        public readonly string $title,
        public readonly string $desc,
        public readonly string $icon,
    ) {
    }


}
