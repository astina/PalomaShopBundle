<?php

namespace Paloma\ShopBundle\Controller\Checkout;

use Paloma\ShopBundle\Controller\AbstractPalomaController;

class CheckoutController extends AbstractPalomaController
{
    public function start()
    {
        return $this->renderPalomaView('checkout/index.html.twig', []);
    }
}