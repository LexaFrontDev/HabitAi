<?php

namespace App\Infrastructure\Service\TwigServices;

use App\Aplication\Dto\Main\SidebarItemDTO;
use App\Domain\Service\TwigServices\SidebarServiceInterface;

class SidebarService implements SidebarServiceInterface
{
    /**
     * @return array<string, array<string, array<SidebarItemDTO>>|array<SidebarItemDTO>>
     */
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
                'group-item' => [
                    new SidebarItemDTO(
                        label: 'Статистика',
                        url: '/profile/statistics',
                        icon: 'Upload/Images/AppIcons/pie-chart.svg',
                    ),
                    new SidebarItemDTO(
                        label: 'Настройки',
                        url: '/settings',
                        icon: 'Upload/Images/Setings/settings.svg',
                    ),
                    new SidebarItemDTO(
                        label: 'Премиюм',
                        url: '/premium',
                        icon: 'Upload/Images/AppIcons/premium-badge-svgrepo-com.svg',
                    ),
                    new SidebarItemDTO(
                        label: 'Выйти из аккаунта',
                        url: '/api/logout',
                        icon: 'Upload/Images/AppIcons/log-out.svg',
                    ),
                ],

            ],
            'item' => [
                new SidebarItemDTO(
                    label: 'Задачи',
                    url: '/Tasks',
                    icon: 'Upload/Images/AppIcons/check-circle.svg',
                ),
                new SidebarItemDTO(
                    label: 'Помодор таймер',
                    url: '/Pomodoro',
                    icon: 'Upload/Images/Pomodor/clock.svg',
                ),
                new SidebarItemDTO(
                    label: 'Привычки',
                    url: '/Habits',
                    icon: 'Upload/Images/Habits/bar-chart-2.svg',
                ),
                new SidebarItemDTO(
                    label: 'Матрица Эйзенхауэра',
                    url: '/matric',
                    icon: 'Upload/Images/AppIcons/hexagon.svg',
                ),
            ],
        ];
    }
}
