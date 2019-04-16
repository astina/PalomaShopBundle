<?php

namespace Paloma\ShopBundle\Tests\Security;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TestTokenStorage implements TokenStorageInterface
{
    /**
     * @var TokenInterface
     */
    private $token;

    public function getToken()
    {
        return $this->token;
    }

    public function setToken(TokenInterface $token = null)
    {
        $this->token = $token;
    }
}