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

namespace Desarrolla2\Bundle\WebBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('content', 'textarea', array(
                    'required' => false,
                    'trim' => true,
                ))
                ->add('userName', 'text', array(
                    'required' => true,
                    'trim' => true,
                ))
                ->add('userEmail', 'text', array(
                    'required' => true,
                    'trim' => true,
                ))
                ->add('captcha', 'captcha', array(
                    'distortion' => false,
                    'charset' => '1234567890',
                    'length' => 3,
                    'invalid_message' => 'Codigo erroneo',
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Desarrolla2\Bundle\WebBundle\Form\Model\ContactModel',
            'csrf_protection' => true,
        ));
    }

    public function getName() {
        return 'contact';
    }

}
