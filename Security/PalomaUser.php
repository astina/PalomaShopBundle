<?php

namespace Paloma\ShopBundle\Security;

use Paloma\Shop\Security\UserDetailsInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PalomaUser implements UserInterface
{
    private $username;

    private $roles = [
        'ROLE_CUSTOMER'
    ];

    /**
     * @var UserDetailsInterface
     */
    private $details;

    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * @return UserDetailsInterface
     */
    public function getDetails(): UserDetailsInterface
    {
        return $this->details;
    }

    /**
     * @param UserDetailsInterface $details
     */
    public function setDetails(UserDetailsInterface $details): void
    {
        $this->details = $details;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
        return null;
    }
}