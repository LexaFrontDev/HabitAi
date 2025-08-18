<?php

namespace App\Domain\Exception\NotFoundException;

use App\Aplication\Dto\ExceptionDto\ActionResult;
use App\Domain\Exception\BaseException\BusinessThrowableInterface;

class NotFoundException extends \RuntimeException implements BusinessThrowableInterface
{
    private bool $isFront;

    public function __construct(string $message = 'Resource not found.', bool $isFront = false)
    {
        parent::__construct($message);
        $this->isFront = $isFront;
    }

    public function getStatusCode(): int
    {
        return  404;
    }

    /**
     * @return array<string, int|string|bool>
     */
    private function getPayload(): array
    {
        return [
            'isFront' => $this->isFront,
            'error' => 'NotFoundException',
            'message' => $this->getMessage(),
            'code' => $this->getStatusCode(),
        ];
    }

    public function toActionResult(): ActionResult
    {
        return new ActionResult(
            status: $this->getStatusCode(),
            json: $this->getPayload()
        );
    }
}
