<?php

namespace App\EventListener;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener implements LoggerAwareInterface
{
    private LoggerInterface $logger;

    public function __construct()
    {
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $data = ["error" => $exception->getMessage()];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        if ($exception instanceof \InvalidArgumentException) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $this->logger->error($exception->getMessage(), ['trace' => $exception->getTraceAsString()]);
        } else if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }

    public function setLogger(LoggerInterface $logger):void
    {
        $this->logger = $logger;
    }
}