<?php

namespace App\Infrastructure\EventListener\Exceptions;

use App\Domain\Exception\ExistException\ExistException;
use App\Domain\Exception\Message\MessageException;
use App\Domain\Exception\NotFoundException\NotFoundException;
use App\Domain\Exception\UsersException\UserNotAuthenticatedException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        if ($e instanceof UserNotAuthenticatedException) {
            $event->setResponse(new RedirectResponse('/login'));
            return;
        }

        $status = 400;
        $payload = [
            'error' => (new \ReflectionClass($e))->getShortName(),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
        ];

        if ($e instanceof NotFoundException) {
            $status = 404;
        } elseif ($e instanceof ExistException) {
            $status = 400;
        }elseif ($e instanceof MessageException){
            $status = 400;
        }  elseif (method_exists($e, 'getStatusCode')) {
            $status = $e->getStatusCode();
        }

        $event->setResponse(new JsonResponse($payload, $status));

        if ($status >= 500) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
        }
    }
}
