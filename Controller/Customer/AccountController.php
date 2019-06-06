<?php

namespace Paloma\ShopBundle\Controller\Customer;

use Paloma\Shop\Security\PalomaSecurityInterface;
use Paloma\ShopBundle\Controller\AbstractPalomaController;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends AbstractPalomaController
{
    public function view(PalomaSecurityInterface $security): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            /*
             * This should not happen because this controller method should be protected
             * in config/packages/security.yaml by 'access_control'.
             */
            return $this->redirectToRoute('paloma_security_login');
        }

        return $this->renderPalomaView('customer/account.html.twig', []);
    }
}