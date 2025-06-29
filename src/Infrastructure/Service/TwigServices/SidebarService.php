<?php

namespace App\Infrastructure\Service\TwigServices;

use App\Aplication\Dto\Main\SidebarItemDTO;
use App\Domain\Service\TwigServices\SidebarServiceInterface;

class SidebarService implements SidebarServiceInterface
{
    public function getItems(): array
    {
        return [
            'group' => [

                'group-settings' => [
                    new SidebarItemDTO(
                        label: 'Профиль',
                        url: '',
                        icon: 'Upload/Images/UsersIcon/user.svg',
                    ),
                ],
                'group-item' =>
                [
                    new SidebarItemDTO(
                        label: 'Настройки профиля',
                        url: '/profile/settings',
                        icon: 'Upload/Images/UsersIcon/user.svg',
                    ),
                    new SidebarItemDTO(
                        label: 'Статистика',
                        url: '/profile/statistics',
                        icon: 'Upload/Images/UsersIcon/user.svg',
                    ),
                    new SidebarItemDTO(
                        label: 'Настройки',
                        url: '/settings',
                        icon: 'Upload/Images/Setings/settings.svg',
                    ),
                    new SidebarItemDTO(
                        label: 'Выйти из аккаунта',
                        url: '/api/logout',
                        icon: 'Upload/Images/Setings/log-out.svg',
                    ),
                ]

            ],
           'item' => [
               new SidebarItemDTO(
                   label: 'Главная',
                   url: '/',
                   icon: 'Upload/Images/HomeIcon/home.svg',
               ),
               new SidebarItemDTO(
                   label: 'Задачи',
                   url: '/tasks',
                   icon: 'Upload/Images/AppIcons/check-circle.svg',
               ),
               new SidebarItemDTO(
                   label: 'Помодор таймер',
                   url: '/pomodoro',
                   icon: 'Upload/Images/Pomodor/clock.svg',
               ),
               new SidebarItemDTO(
                   label: 'Привычки',
                   url: '/habits',
                   icon: 'Upload/Images/Habits/bar-chart-2.svg',
               ),

           ]
        ];
    }
}
