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
use Symfony\Bundle\TwigBundle\TwigEngine;
use DateTime;
use Exception;
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
     * @var \Exception
     */
    protected $exception;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $to;

    /**
     * @var TwigEngine
     */
    protected $twig;

    /**
     * @var string
     */
    protected $env;

    /**
     * @var array
     */
    protected $ignoredExceptions = array(
        'Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException',
        'Symfony\Component\HttpKernel\Exception\NotFoundHttpException'
    );

    /**
     * @var array
     */
    protected $ignoredEnvironment = array(

        'test', 'dev'
    );

    /**
     * @param Swift_Mailer $mailer
     * @param TwigEngine   $twig
     * @param string       $from
     * @param string       $to
     * @param string       $environment
     */
    public function __construct(Swift_Mailer $mailer, TwigEngine $twig, $from, $to, $environment)
    {
        $this->date = new DateTime();
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->from = $from;
        $this->to = $to;
        $this->env = $environment;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->exception = $event->getException();

        if (!isset($_SERVER['SERVER_NAME'])) {
            return;
        }

        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }

        if (in_array($this->getEnv(), $this->getIgnoredEnvironment())) {
            return;
        }

        if (in_array(get_class($this->getException()), $this->getIgnoredExceptions())) {
            return;
        }

        $this->sendExceptionMessage();
    }

    /**
     * @return string
     */
    protected function getBody()
    {
        return $this->twig->render(
            'WebBundle:Mail:exception.html.twig',
            array(
                'exception' => $this->getException(),
                'path' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                'datetime' => $this->getDateTime(),
                'get' => print_r($_GET, true),
                'post' => print_r($_POST, true),
                'server' => print_r($_SERVER, true),
            )
        );
    }

    /**
     * @return int
     */
    protected function sendExceptionMessage()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($this->getSubject())
            ->setFrom($this->getFrom())
            ->setTo($this->getTo())
            ->setBody($this->getBody(), 'text/html');

        return $this->getMailer()->send($message);
    }

    /**
     * @return string
     */
    protected function getEnv()
    {
        return $this->env;
    }

    /**
     * @return \Exception
     */
    protected function getException()
    {
        return $this->exception;
    }

    /**
     * @return string
     */
    protected function getFrom()
    {
        return $this->from;
    }

    /**
     * @return \Swift_Mailer
     */
    protected function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @return string
     */
    protected function getSubject()
    {
        return
            '[' . strtolower($_SERVER['SERVER_NAME']) . '] ' .
            '[' . $this->getDateTime() . '] - ' .
            $this->getException()->getMessage();
    }

    /**
     * @return string
     */
    protected function getTo()
    {
        return $this->to;
    }

    /**
     * @return array
     */
    protected function getIgnoredEnvironment()
    {
        return $this->ignoredEnvironment;
    }

    /**
     * @return array
     */
    protected function getIgnoredExceptions()
    {
        return $this->ignoredExceptions;
    }

    /**
     * @return mixed
     */
    protected function getDateTime()
    {
        return $this->date->format('Y-m-d H:i:s');
    }

}
