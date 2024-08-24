<?php

namespace App\EventListener;

use App\Response\ApiResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsEventListener]
class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        if ($throwable instanceof NotFoundHttpException) {
            $event->setResponse(new JsonResponse(
                new ApiResponse('error', [], ['Invalid URL']),
                Response::HTTP_NOT_FOUND
            ));
        } else if ($throwable instanceof MethodNotAllowedHttpException) {
            $event->setResponse(new JsonResponse(
                new ApiResponse('error', [], ['Invalid request method']),
                Response::HTTP_METHOD_NOT_ALLOWED
            ));
        } else {
            $exceptionMessages = explode("\n", $throwable->getMessage());
            $event->setResponse(new JsonResponse(
                new ApiResponse('error', [], $exceptionMessages),
                Response::HTTP_BAD_REQUEST
            ));
        }
    }
}