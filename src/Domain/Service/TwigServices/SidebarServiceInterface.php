<?php

namespace App\Domain\Service\TwigServices;

use App\Aplication\Dto\Main\SidebarItemDTO;

interface SidebarServiceInterface
{
    /**
     * @return array<string, array<string, array<SidebarItemDTO>>|array<SidebarItemDTO>>
     */
    public function getItems(): array;
}
