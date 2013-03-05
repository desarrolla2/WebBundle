<?php

/**
 * This file is part of the planetubuntu proyect.
 * 
 * Copyright (c)
 * Daniel González Cerviño <daniel.gonzalez@externos.seap.minhap.es>  
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\WebBundle\Handler;

use \Desarrolla2\Bundle\WebBundle\Form\Model\ContactModel;
use \Symfony\Bundle\TwigBundle\TwigEngine;
use \Swift_Message;
use \Swift_Mailer;

/**
 * 
 * Description of Contact
 *
 * @author : Daniel González Cerviño <daniel.gonzalez@externos.seap.minhap.es>  
 * @file : Contact.php , UTF-8
 * @date : Mar 5, 2013 , 4:53:44 PM
 */
class Contact {

    /**
     * @var \Swift_Mailer 
     */
    protected $mailer;

    /**
     * @var \Symfony\Bundle\TwigBundle\TwigEngine 
     */
    protected $templating;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $to;

    /**
     * 
     * @param \Swift_Mailer $mailer
     * @param \Symfony\Bundle\TwigBundle\TwigEngine $templating
     * @param string $subjet
     * @param string $from
     * @param string $to
     */
    public function __construct(Swift_Mailer $mailer, TwigEngine $templating, $subjet, $to) {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->subject = 'Formulario de Contacto';
        $this->to = 'daniel.gonzalez@freelancemadrid.es';
    }

    /**
     * @param \Desarrolla2\Bundle\WebBundle\Form\Model\ContactModel $data
     */
    public function send(ContactModel $data) {
        $body = $this->renderTemplate($data);
        $message = $this->getMessage();
        $message->setTo($this->to);
        $message->setFrom($this->to);
        $message->setSubject($this->subject);
        $message->setBody($body);
        return $this->mailer->send($message);
    }

    /**
     * 
     * @return \Swift_Message
     */
    protected function getMessage() {
        return Swift_Message::newInstance();
    }

    /**
     * 
     * @param \Desarrolla2\Bundle\WebBundle\Form\Model\ContactModel $data
     */
    protected function renderTemplate(ContactModel $data) {
        return $this->templating->render('WebBundle:Contact:email.html.twig', array(
                    'email' => $data->getUserEmail(),
                    'name' => $data->getUserName(),
                    'content' => $data->getContent(),
        ));
    }

}
