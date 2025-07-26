<?php

namespace App\Domain\Exception\ExistException;

use App\Application\Dto\ExceptionDto\ActionResult;
use App\Domain\Exception\BaseException\BusinessThrowableInterface;

final class ExistException extends \RuntimeException implements BusinessThrowableInterface
{
    public function __construct(string $message = 'Resource already exists.', int $code = 404)
    {
        parent::__construct($message, $code);
    }

    public function getStatusCode(): int
    {
        return $this->getCode();
    }


    private function getPayload(): array
    {
        return [
            'error' => 'ExistException',
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ];
    }

    public function toActionResult(): ActionResult
    {
        return new ActionResult(
            status: $this->getStatusCode(),
            json: $this->getPayload(),
        );
    }
}
