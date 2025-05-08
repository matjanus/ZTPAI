<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $statusCode = 500;
        $message = 'Unexpected error';

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage() ?: $this->getDefaultMessage($statusCode);
        }

        $response = new JsonResponse([
            'error' => $message,
        ], $statusCode);

        $event->setResponse($response);
    }

    private function getDefaultMessage(int $statusCode): string
    {
        return match ($statusCode) {
            403 => 'Access denied',
            404 => 'Resource not found',
            401 => 'Unauthorized',
            default => 'Unexpected error',
        };
    }
}