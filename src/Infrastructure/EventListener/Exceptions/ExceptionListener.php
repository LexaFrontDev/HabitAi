<?php

namespace App\Infrastructure\EventListener\Exceptions;

use App\Aplication\Dto\ExceptionDto\ActionResult;
use App\Domain\Exception\BaseException\BusinessThrowableInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\Response;

class ExceptionListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof BusinessThrowableInterface) {
            $result = $exception->toActionResult();

            $response = $this->buildResponseFromActionResult($result);
            $event->setResponse($response);

            if ($result->status >= 500) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            }

            return;
        }

        $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;

        $payload = [
            'error' => (new \ReflectionClass($exception))->getShortName(),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ];

        $event->setResponse(new JsonResponse($payload, $status));

        if ($status >= 500) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }
    }

    private function buildResponseFromActionResult(ActionResult $result): Response
    {
        $status = ($result->status >= 100 && $result->status <= 599) ? $result->status : 500;

        if (null !== $result->redirectUrl) {
            return new RedirectResponse($result->redirectUrl, $status);
        }

        if (null !== $result->json) {
            return new JsonResponse($result->json, $status);
        }

        return new JsonResponse(['message' => 'Unhandled error'], $status);
    }
}
