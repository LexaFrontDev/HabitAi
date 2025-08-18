<?php

namespace App\Domain\Exception\BaseException;

use App\Aplication\Dto\ExceptionDto\ActionResult;

interface BusinessThrowableInterface
{
    public function getStatusCode(): int;

    public function toActionResult(): ActionResult;
}
