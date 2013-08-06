<?php

/**
 * This file is part of the desarrolla2 project.
 * 
 * Copyright (c)
 * Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>  
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
use Desarrolla2\Bundle\WebBundle\Form\Handler\ContactHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
            $handler = new ContactHandler($request, $form, $this->get('web.contact.handler'));
            if ($handler->process()) {
                $this->get('session')
                        ->getFlashBag()
                        ->add('success', 'Hemos recibido su mensaje');
                return new RedirectResponse($this->generateUrl('_message'), 302);
            }
        }
        return array(
            'form' => $form->createView(),
            'title' => $this->container->getParameter('web.contact.title'),
        );
    }

}