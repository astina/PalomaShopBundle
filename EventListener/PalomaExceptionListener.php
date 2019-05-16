<?php

namespace Paloma\ShopBundle\EventListener;

use Paloma\Shop\Error\PalomaException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

/**
 * Create appropriate HTTP exception for Paloma exceptions
 */
class PalomaExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (!($exception instanceof PalomaException)) {
            return;
        }

        /** @var $exception PalomaException */
        switch ($exception->getHttpStatus()) {

            case Response::HTTP_BAD_REQUEST:
                $event->setException(new BadRequestHttpException($exception->getMessage(), $exception));
                break;

            case Response::HTTP_NOT_FOUND:
                $event->setException(new NotFoundHttpException($exception->getMessage(), $exception));
                break;

            case Response::HTTP_BAD_GATEWAY:
            case Response::HTTP_GATEWAY_TIMEOUT:
            case Response::HTTP_SERVICE_UNAVAILABLE:
                $event->setException(new ServiceUnavailableHttpException(10, 'Service Unavailable', $exception));
                break;

            default:
                $event->setException(new HttpException($exception->getHttpStatus(), $exception->getMessage(), $exception));
        }
    }
}