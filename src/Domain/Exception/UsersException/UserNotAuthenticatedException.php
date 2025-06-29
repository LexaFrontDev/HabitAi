<?php

namespace App\Domain\Exception\UsersException;

class UserNotAuthenticatedException extends \RuntimeException
{
    public function __construct(string $message = 'User is not authenticated.')
    {
        parent::__construct($message);
    }
}