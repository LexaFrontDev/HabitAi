<?php

namespace App\Domain\Service\AiService;

interface AiInterface
{
    /**
     * @return array<string, mixed> Возвращает декодированный JSON как массив
     */
    public function integration(string $prompt): array;

    public function generateText(string $prompt): string;
}
