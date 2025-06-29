<?php

namespace App\Domain\Exception\ExistException;

class ExistException extends \RuntimeException
{
    public function __construct(string $message = 'Resource is exist .')
    {
        parent::__construct($message);
    }
}
