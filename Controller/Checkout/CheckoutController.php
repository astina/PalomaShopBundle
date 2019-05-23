<?php

namespace Paloma\ShopBundle\Controller\Checkout;

use Paloma\Shop\Checkout\CheckoutInterface;
use Paloma\Shop\Error\CartIsEmpty;
use Paloma\Shop\Security\PalomaSecurityInterface;
use Paloma\ShopBundle\Controller\AbstractPalomaController;
use Paloma\ShopBundle\Serializer\PalomaSerializer;
use Paloma\ShopBundle\Serializer\SerializationConstants;

class CheckoutController extends AbstractPalomaController
{
    public function start(PalomaSecurityInterface $security)
    {
        $state = 'auth';

        if ($security->getCustomer()) {
            $state = 'delivery';
        }

        return $this->redirectToRoute('paloma_checkout_state', ['state' => $state]);
    }

    public function state(string $state, PalomaSerializer $serializer, CheckoutInterface $checkout)
    {
        try {
            $order = $checkout->getOrderDraft();
        } catch (CartIsEmpty $e) {
            return $this->redirectToRoute('paloma_catalog_home');
        }

        return $this->render('@PalomaShop/checkout/index.html.twig', [
            'order_json' => $serializer->serialize($order, SerializationConstants::OPTIONS_ORDER_DRAFT),
            'state' => $state,
        ]);
    }
}