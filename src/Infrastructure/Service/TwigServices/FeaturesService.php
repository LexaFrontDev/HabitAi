<?php

namespace App\Infrastructure\Service\TwigServices;

use App\Aplication\Dto\Main\FeatureDTO;
use App\Domain\Service\TwigServices\FeaturesServiceInterface;

class FeaturesService implements FeaturesServiceInterface
{
    /**
     * @return FeatureDTO[]
     */
    public function getFeatures(): array
    {
        return [
            new FeatureDTO(
                title: 'Таймер помодор',
                desc: 'Фокусируйся эффективно.',
                icon: 'Upload/Images/Pomodor/clock.svg',
                url: '/Pomodoro',
            ),
            new FeatureDTO(
                title: 'Список Привычек',
                desc: 'Формируй полезные действия.',
                icon: 'Upload/Images/Habits/bar-chart-2.svg',
                url: '/Habits',
            ),
            new FeatureDTO(
                title: 'Матрица Эйзенхауэра',
                desc: 'Сортируй задачи умно.',
                icon: 'Upload/Images/AppIcons/hexagon.svg',
                url: '/eisenhower',
            ),
            new FeatureDTO(
                title: 'Планировщик',
                desc: 'Синхронизируй календарь.',
                icon: 'calendar.svg',
                url: '/planner',
            ),
            new FeatureDTO(
                title: 'Список Задач',
                desc: 'Управляй своим списком дел.',
                icon: 'Tasks.svg',
                url: '/Tasks',
            ),
        ];
    }
}
