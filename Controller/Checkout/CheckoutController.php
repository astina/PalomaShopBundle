<?php

namespace Paloma\ShopBundle\Controller\Checkout;

use Paloma\Shop\Checkout\CheckoutInterface;
use Paloma\Shop\Error\CartIsEmpty;
use Paloma\ShopBundle\Controller\AbstractPalomaController;
use Paloma\ShopBundle\PalomaSerializer;

class CheckoutController extends AbstractPalomaController
{
    public function start()
    {
        return $this->redirectToRoute('paloma_checkout_state', [ 'state' => 'auth' ]);
    }

    public function state(string $state, PalomaSerializer $serializer, CheckoutInterface $checkout)
    {
        try {
            $order = $checkout->getOrderDraft();
        } catch (CartIsEmpty $e) {
            // TODO flash message
            return $this->redirectToRoute('paloma_catalog_home');
        }

        return $this->render('@PalomaShop/checkout/index.html.twig', [
            'order_json' => $serializer->serialize($order, [
                'exclude' => [
                    'customer' => [
                        'id',
                        'userId'
                    ]
                ]
            ]),
            'state' => $state,
        ]);
    }
}