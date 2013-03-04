<?php

/**
 * This file is part of the desarrolla2 proyect.
 * 
 * Copyright (c)
 * Daniel González Cerviño <daniel.gonzalez@externos.seap.minhap.es>  
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Desarrolla2\Bundle\WebBundle\Form\Type\ContactType;
use Desarrolla2\Bundle\WebBundle\Form\Model\ContactModel;

/**
 * 
 * Description of ContactController
 *
 */
class ContactController extends Controller {

    /**
     *
     *
     * @Route("/contact", name="_contact")
     * @Template()
     */
    public function indexAction(Request $request) {

        $form = $this->createForm(new ContactType(), new ContactModel());
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $this->sendEmail($form->getData());
                $this->get('session')
                        ->getFlashBag()
                        ->add('notice', 'Hemos recibido su mensaje');
            }
        }
        return array(
            'form' => $form->createView(),
        );
    }

    protected function sendEmail($data) {
        $message = \Swift_Message::newInstance()
                ->setSubject('Formulario de contacto')
                ->setFrom('daniel.gonzalez@freelancemadrid.es')
                ->setTo('daniel.gonzalez@freelancemadrid.es')
                ->setReplyTo($data->getUserEmail(), $data->getUserName())
                ->setBody($data->getContent());
        $this->get('mailer')->send($message);
    }

}