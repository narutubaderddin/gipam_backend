<?php

namespace App\EventSubscriber;

use App\Exception\FormValidationException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /** @var ExceptionEvent $event */
    protected $event;

    public function processException(ExceptionEvent $event)
    {
        $this->event = $event;
        $exception = $this->event->getException();

        if ($exception instanceof ForeignKeyConstraintViolationException) {
            $message = sprintf("Can't delete a resource : The Resource has a relationship");
            $this->setResponse($message);

            return;
        }
        if ($exception instanceof FormValidationException){
            $this->event->setResponse($exception->getJsonResponse());

            return;
        }
        if ($exception instanceof HttpException){
            $this->setResponse($exception->getMessage(), $exception->getStatusCode());

            return;
        }
        if ($exception instanceof \Exception){
            $this->setResponse($exception->getMessage());

            return;
        }
    }

    /**
     * @return \array[][]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['processException', 255]]
        ];
    }

    /**
     * @param string $message
     * @param int $code
     * @param array $param
     */
    private function setResponse(string $message, int $code = Response::HTTP_BAD_REQUEST, array $param = []) : void
    {
        $data['code'] = $code;
        $data['message'] = $message;
        $this->event->setResponse(new JsonResponse($data, $code));
    }
}