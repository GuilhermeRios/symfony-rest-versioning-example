<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\AuthCode as BaseAuthCode;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthCodeRepository")
 */
class AuthCode extends BaseAuthCode
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    protected $user;
}
