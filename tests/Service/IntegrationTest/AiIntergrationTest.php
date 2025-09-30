<?php

namespace App\Tests\Service\IntegrationTest;

use App\Domain\Service\AiService\AiInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AiIntegrationTest extends KernelTestCase
{
    private AiInterface $ai;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->ai = self::getContainer()->get(AiInterface::class);
    }

    public function testGenerateTextBasicPrompt(): void
    {
        $prompt = "у тебя спрашивает пользователь Noas о его статистике по продуктивности. Текущие привычки:\n1. Утренняя пробежка — цель 5 км в день, трекинг: 20 км за неделю, 180 км за месяц, 400 км за 5 месяцев.\n2. Чтение — цель 10 страниц в день, трекинг: 70 страниц за неделю, 300 страниц за месяц, 1500 страниц за 5 месяцев.\n3. Питье воды — цель 2 литра в день, трекинг: 14 литров за неделю, 60 литров за месяц, 300 литров за 5 месяцев.\n\nВымышленные цели:\n- Бегать 5 км каждый день\n- Читать 10 страниц ежедневно\n- Пить достаточно воды каждый день\n- Планировать день заранее\n- Делать медитацию по 10 минут\n\nИнструкция для GIMINI: анализируй статистику, предложи улучшения продуктивности и, если пользователь хочет добавить новые привычки, ответь JSON-командой в формате: {\"recommendations\":[], \"newHabits\":[], \"updateCommand\":{}}. Вопрос пользователя: 'Что ты думаешь о моей продуктивности и какие привычки мне добавить?'";

        $response = $this->ai->generateText($prompt);

        fwrite(STDOUT, "\nPROMPT: ".$prompt);
        fwrite(STDOUT, "\nRESPONSE (raw): ".print_r($response, true));
        fwrite(STDOUT, "\nRESPONSE (json): ".json_encode(
            $response,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ));

        $this->assertNotEmpty($response, 'AI response should not be empty');
    }
}
