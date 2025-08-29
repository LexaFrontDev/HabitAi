<?php

namespace App\Infrastructure\DataFixtures\V1Fixtures;

use App\Domain\Entity\Premium\Benefits;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BenefitsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $features = [
            [
                'title' => 'ИИ помогает достигать целей',
                'desc' => 'Интеллектуальный помощник подсказывает приоритеты и экономит время',
                'icon' => '/icons/ai.svg',
            ],
            [
                'title' => '50 привычек и задач в день',
                'desc' => 'Достаточно, чтобы охватить все сферы жизни',
                'icon' => '/icons/checklist.svg',
            ],
            [
                'title' => 'Кастомный календарь',
                'desc' => 'Гибкий инструмент под твоё расписание и стиль',
                'icon' => '/icons/calendar.svg',
            ],
            [
                'title' => 'Система рейтингов',
                'desc' => 'Соревнуйся с собой и другими',
                'icon' => '/icons/rating.svg',
            ],
            [
                'title' => 'Темы и настройка',
                'desc' => 'Темная/светлая тема, фоны, звуки и многое другое',
                'icon' => '/icons/theme.svg',
            ],
            [
                'title' => 'Уведомления и напоминания',
                'desc' => 'Никогда не пропусти важную задачу',
                'icon' => '/icons/bell.svg',
            ],
        ];

        foreach ($features as $feature) {
            $plan = new Benefits();
            $plan->setTitle($feature['title']);
            $plan->setDesc($feature['desc']);
            $plan->setIconPath($feature['icon']);

            $manager->persist($plan);
        }

        $manager->flush();
    }
}
