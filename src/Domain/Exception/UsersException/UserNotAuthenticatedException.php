<?php

namespace App\Domain\Exception\UsersException;

use App\Application\Dto\ExceptionDto\ActionResult;
use App\Domain\Exception\BaseException\BusinessThrowableInterface;

class UserNotAuthenticatedException extends \RuntimeException implements BusinessThrowableInterface
{
    public function __construct(string $message = 'User is not authenticated.')
    {
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return 301;
    }

    public function toActionResult(): ActionResult
    {
        return new ActionResult(
            status: $this->getStatusCode(),
            redirectUrl: '/ru/login'
        );
    }
}