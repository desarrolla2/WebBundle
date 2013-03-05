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
use \Swift_Message;

/**
 * 
 * Description of Contact
 *
 * @author : Daniel González Cerviño <daniel.gonzalez@externos.seap.minhap.es>  
 * @file : Contact.php , UTF-8
 * @date : Mar 5, 2013 , 4:53:44 PM
 */
class Contact {

    protected $mailer;

    public function __construct($mailer) {
        $this->mailer = $mailer;
    }

    public function send(ContactModel $data) {
        
    }

    protected function sendEmail($data) {
        $message = Swift_Message::newInstance()
                ->setSubject('Formulario de contacto')
                ->setFrom('daniel.gonzalez@freelancemadrid.es')
                ->setTo('daniel.gonzalez@freelancemadrid.es')
                ->setReplyTo($data->getUserEmail(), $data->getUserName())
                ->setBody($data->getContent());
        $this->get('mailer')->send($message);
    }

}
