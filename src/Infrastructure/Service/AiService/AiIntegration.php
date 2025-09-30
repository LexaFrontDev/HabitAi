<?php

namespace App\Infrastructure\Service\AiService;

use App\Domain\Service\AiService\AiInterface;

class AiIntegration implements AiInterface
{
    private string $apiKey;
    private string $headerName;
    private string $aiURL;

    public function __construct(string $apiKey, string $headerName, string $aiUrl)
    {
        $this->apiKey = $apiKey;
        $this->headerName = $headerName;
        $this->aiURL = $aiUrl;
    }

    /**
     * @return array<string, mixed> Возвращает декодированный JSON как массив
     *
     * @throws \JsonException
     */
    public function integration(string $prompt): array
    {
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
        ];

        $ch = curl_init($this->aiURL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "{$this->headerName}: {$this->apiKey}",
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        $json = json_encode($data, JSON_THROW_ON_ERROR);
        if (false === $json) {
            throw new \RuntimeException('Failed to encode data to JSON: '.json_last_error_msg());
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new \RuntimeException("Curl error: {$err}");
        }


        if (false === $response) {
            throw new \RuntimeException('Curl response is false');
        }

        return json_decode((string) $response, true, 512, JSON_THROW_ON_ERROR);
    }

    public function generateText(string $prompt): string
    {
        $response = $this->integration($prompt);

        if (!isset($response['candidates'][0]['content']['parts'])) {
            throw new \RuntimeException('No content in API response');
        }

        $parts = array_column($response['candidates'][0]['content']['parts'], 'text');

        return trim(implode("\n", $parts));
    }
}
