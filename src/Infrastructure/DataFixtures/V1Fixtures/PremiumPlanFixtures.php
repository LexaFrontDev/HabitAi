<?php

namespace App\Infrastructure\DataFixtures\V1Fixtures;

use App\Domain\Entity\Premium\PremiumPlans;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PremiumPlanFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $plans = [
            [
                'name' => 'Free',
                'desc' => 'Базовые функции для старта',
                'price' => '0₸ / мес',
                'features' => ['20 задач в день', '5 привычек', 'Матрица Эйзенхауэра', 'Помодоро', 'Базовый Кастомный фон'],
                'highlight' => true,
            ],
            [
                'name' => 'Pro',
                'desc' => 'Для продуктивной работы',
                'price' => '999₸ / мес',
                'features' => [
                    '50 задач в день',
                    '50 привычек',
                    'Кастомный фон',
                    'ИИ-помощник',
                    'канбан',
                    'Больше звуков для помодора',
                    'полный Кастомный фон и возможность добавлять свой фоны',
                ],
                'highlight' => true,
            ],
            [
                'name' => 'Pro Год',
                'desc' => 'Экономия и приоритет',
                'price' => '9 999₸ / год',
                'features' => ['Всё из Pro', 'Приоритетная поддержка'],
                'highlight' => true,
            ],
        ];

        foreach ($plans as $data) {
            $plan = new PremiumPlans();
            $plan->setName($data['name']);
            $plan->setDesc($data['desc']);
            $plan->setPrice($data['price']);
            $plan->setFeatures($data['features']);
            $plan->setHighlight($data['highlight']);

            $manager->persist($plan);
        }

        $manager->flush();
    }
}
