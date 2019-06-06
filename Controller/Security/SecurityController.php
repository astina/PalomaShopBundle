<?php

namespace Paloma\ShopBundle\Controller\Security;

use Paloma\Shop\Customers\CustomersInterface;
use Paloma\ShopBundle\Controller\AbstractPalomaController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractPalomaController
{
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->renderPalomaView(
            'security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }

    public function confirmPasswordReset(CustomersInterface $customers, Request $request): Response
    {
        $token = $request->get('token');

        $exists = $customers->existsPasswordResetToken($token);

        return $this->renderPalomaView(
            'security/password_reset.html.twig',
            [
                'token' => $token,
                'token_valid' => $exists,
            ]
        );
    }
}
