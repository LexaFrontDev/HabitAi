<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\Language\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LanguageFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $languages = [
            ['name' => 'Русский', 'prefix' => 'ru'],
            ['name' => 'English', 'prefix' => 'en'],
            ['name' => 'Қазақ тілі', 'prefix' => 'kz'],
            ['name' => 'Deutsch', 'prefix' => 'de'],
            ['name' => 'Кыргыз тили', 'prefix' => 'kg'],
            ['name' => 'O‘zbek tili', 'prefix' => 'uz'],
            ['name' => '한국어', 'prefix' => 'ko'],
            ['name' => '日本語', 'prefix' => 'ja'],
        ];

        foreach ($languages as $langData) {
            $existing = $manager->getRepository(Language::class)
                ->findOneBy(['name' => $langData['name']]);

            if ($existing) {
                continue;
            }
            $language = new Language();
            $language->setName($langData['name']);
            $language->setPrefix($langData['prefix']);
            $manager->persist($language);
        }

        $manager->flush();
    }
}
