<?php

namespace Paloma\ShopBundle\Controller\Customer;

use Paloma\Shop\Customers\CustomersInterface;
use Paloma\Shop\Error\InvalidConfirmationToken;
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

    public function confirm(CustomersInterface $customers, Request $request)
    {
        $token = $request->get('token');
        $valid = null;

        try {

            $customers->confirmEmailAddress($token);

            $valid = true;

        } catch (InvalidConfirmationToken $e) {
            $valid = false;
        }

        return $this->renderPalomaView('customer/register_confirm.html.twig', [
            'success' => $valid,
        ]);
    }
}