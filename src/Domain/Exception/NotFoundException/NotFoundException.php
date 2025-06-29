<?php

namespace App\Domain\Exception\NotFoundException;


class NotFoundException extends \RuntimeException
{
    public function __construct(string $message = 'Resource not found.')
    {
        parent::__construct($message);
    }
}
