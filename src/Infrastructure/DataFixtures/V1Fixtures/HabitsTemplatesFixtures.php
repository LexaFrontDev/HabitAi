<?php

namespace App\Infrastructure\DataFixtures\V1Fixtures;

use App\Domain\Entity\Habits\HabitsTemplates;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HabitsTemplatesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {


        $habitTemplates = [
            [
                'title' => 'Ранний подъем',
                'quote' => 'Кто рано встает, тому Бог дает!',
                'notification' => '06:30',
                'datesType' => 'daily',
            ],
            [
                'title' => 'Утренняя пробежка',
                'quote' => 'Бег - это жизнь!',
                'notification' => '10:30',
                'datesType' => 'daily',
            ],
            [
                'title' => 'Чтение книги',
                'quote' => 'Книги - корабли мысли',
                'notification' => '16:30',
                'datesType' => 'daily',
            ],
            [
                'title' => 'Медитация',
                'quote' => 'Тишина - великий учитель',
                'notification' => '14:30',
                'datesType' => 'daily',
            ],
        ];

        foreach ($habitTemplates as $data) {
            $existing = $manager->getRepository(HabitsTemplates::class)
                ->findOneBy(['title_template' => $data['title']]);

            if ($existing) {
                continue;
            }
            $habit = new HabitsTemplates();
            $habit->setTitleTemplate($data['title']);
            $habit->setQuoteTemplate($data['quote']);
            $habit->setNotificationTemplate($data['notification']);
            $habit->setDatesTypeTemplate($data['datesType']);
            $manager->persist($habit);
        }

        $manager->flush();
    }
}
