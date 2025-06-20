<?php

namespace App\Infrastructure\Service\TwigServices;

use App\Aplication\Dto\Main\SidebarItemDTO;
use App\Domain\Service\TwigServices\SidebarServiceInterface;

class SidebarService implements SidebarServiceInterface
{
    public function getItems(): array
    {
        return [
            new SidebarItemDTO(
                label: 'Главная',
                url: '/',
                icon: 'StorageImages/Icons/Statistic.svg',
            ),
            new SidebarItemDTO(
                label: 'Помодор таймер',
                url: '/pomodor',
                icon: 'StorageImages/Icons/PomodorIcons.svg',
            ),
            new SidebarItemDTO(
                label: 'Привычки',
                url: '/habits/list',
                icon: 'StorageImages/Icons/HabitsIcon.svg',
            ),
            new SidebarItemDTO(
                label: 'Настройки',
                url: '/settings',
                icon: 'StorageImages/Icons/SetingsIcon.svg',
            ),
        ];
    }
}
