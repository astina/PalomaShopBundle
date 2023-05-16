<?php

namespace Paloma\ShopBundle\Security;

use Paloma\Shop\Security\UserDetailsInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PalomaUser implements UserInterface
{
    private string $username;

    private array $roles = [
        'ROLE_CUSTOMER'
    ];

    private UserDetailsInterface $details;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getDetails(): ?UserDetailsInterface
    {
        return $this->details;
    }

    public function setDetails(UserDetailsInterface $details): void
    {
        $this->details = $details;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }
}