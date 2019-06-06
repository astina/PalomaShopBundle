<?php

namespace Paloma\ShopBundle\Controller\Customer;

use Paloma\Shop\Customers\CustomersInterface;
use Paloma\Shop\Error\InvalidConfirmationToken;
use Paloma\Shop\Security\PalomaSecurityInterface;
use Paloma\ShopBundle\Controller\AbstractPalomaController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends AbstractPalomaController
{
    public function view(): Response
    {
        return $this->renderPalomaView('customer/register.html.twig', []);
    }

    public function success(): Response
    {
        return $this->renderPalomaView('customer/register_success.html.twig', []);
    }

    public function confirm(CustomersInterface $customers, PalomaSecurityInterface $security, Request $request)
    {
        $token = $request->get('token');
        $valid = null;

        try {

            $user = $customers->confirmEmailAddress($token);

            $security->setUser($user);

            $valid = true;

        } catch (InvalidConfirmationToken $e) {
            $valid = false;
        }

        return $this->renderPalomaView('customer/register_confirm.html.twig', [
            'success' => $valid,
        ]);
    }
}