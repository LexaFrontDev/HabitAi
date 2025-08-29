<?php

namespace App\Infrastructure\DataFixtures\V1Fixtures;

use App\Domain\Entity\Premium\Faqs;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FAQFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faqs = [
            [
                'question' => 'Можно ли вернуть деньги после покупки?',
                'answer' => 'Да, вы можете оформить возврат в течение 14 дней после оплаты. Просто отправьте нам запрос через раздел "Обратная связь" в приложении. Если вы оформили подписку через App Store или Google Play, отмену и возврат необходимо оформить через соответствующую платформу.',
            ],
            [
                'question' => 'Что будет с моими данными, если я не продлю подписку?',
                'answer' => 'Мы не удалим ваши привычки, задачи и статистику. Всё останется сохранено. Если количество привычек превышает лимит бесплатной версии, привычки с низким приоритетом будут автоматически перемещены в архив. Доступ к премиум-функциям будет временно ограничен до продления подписки.',
            ],
            [
                'question' => 'Как отменить подписку?',
                'answer' => 'Если вы оформили подписку через Google Play или App Store — откройте соответствующее приложение, перейдите в раздел "Подписки" и отмените TaskFlow PremiumUseCases. Если подписка оформлена напрямую, просто напишите нам через "Обратную связь".',
            ],
            [
                'question' => 'Что делать, если остались вопросы?',
                'answer' => 'Напишите нам в поддержку через приложение или на почту support@taskflow.app. Мы всегда готовы помочь!',
            ],
        ];

        foreach ($faqs as $data) {
            $faq = new Faqs();
            $faq->setQuestion($data['question']);
            $faq->setAnswer($data['answer']);

            $manager->persist($faq);
        }

        $manager->flush();
    }
}
