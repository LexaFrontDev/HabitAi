<?php

namespace App\Aplication\Dto\Main;

class SidebarItemDTO
{
    public function __construct(
        public string $label,
        public string $url,
        public string $icon,
    ) {
    }
}
