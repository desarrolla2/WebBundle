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

namespace Desarrolla2\Bundle\WebBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ContactModel {

    /**
     * @var string $content
     * @Assert\NotBlank()
     * @Assert\MinLength( limit=5 )
     */
    public $content;

    /**
     * @var string $userName
     * @Assert\NotBlank()
     * @Assert\MinLength( limit=3 )
     *
     */
    public $userName;

    /**
     * @var string $userEmail
     * @Assert\Email(
     *     message = "'{{ value }}' no es un email válido.",
     *     checkMX = true
     * )
     */
    public $userEmail;

    public function __construct() {
        
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function getUserEmail() {
        return $this->userEmail;
    }

    public function setUserEmail($userEmail) {
        $this->userEmail = $userEmail;
    }

}
