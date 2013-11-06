<?php

/**
 * This file is part of the desarrolla2 project.
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\WebBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Swift_Mailer;

/**
 *
 * Description of ErrorListener
 *
 * @author : Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>
 */
class ExceptionListener
{

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @param Swift_Mailer $mailer
     * @param string       $from
     * @param string       $to
     * @param string       $subject
     * @param string       $environment
     */
    public function __construct(Swift_Mailer $mailer, $from, $to, $subject, $environment = 'prod')
    {
        $this->mailer = $mailer;
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->environment = $environment;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($this->environment == 'dev' || $this->environment == 'test') {
            return;
        }
        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }

        $exception = $event->getException();
        if (get_class($exception) != 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException') {
            $message =
                'route : http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . PHP_EOL .
                'message        : ' . $exception->getMessage() . PHP_EOL .
                'code           : ' . $exception->getCode() . PHP_EOL .
                'trace          : ' . $exception->getTraceAsString() . PHP_EOL .
                'previous       : ' . $exception->getPrevious() . PHP_EOL .
                'file           : ' . $exception->getFile() . PHP_EOL .
                'line           : ' . $exception->getLine() . PHP_EOL .
                'GET            : ' . PHP_EOL . print_r($_GET, true) . PHP_EOL .
                'POST           : ' . PHP_EOL . print_r($_POST, true) . PHP_EOL .
                'SERVER         : ' . PHP_EOL . print_r($_SERVER, true) . PHP_EOL;

            $message = \Swift_Message::newInstance()
                ->setSubject($this->subject . ' ' . $exception->getMessage())
                ->setFrom($this->from)
                ->setTo($this->to)
                ->setBody($message);
            $this->mailer->send($message);
        }
    }
}
