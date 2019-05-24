<?php

namespace Paloma\ShopBundle\EventListener;

use Exception;
use Paloma\Shop\Checkout\CheckoutInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\CartIsEmpty;
use Paloma\Shop\Security\PalomaSecurityInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class PalomaAuthenticationListener implements EventSubscriberInterface
{
    /**
     * @var PalomaSecurityInterface
     */
    private $security;

    /**
     * @var CheckoutInterface
     */
    private $checkout;

    public function __construct(PalomaSecurityInterface $security, CheckoutInterface $checkout)
    {
        $this->security = $security;
        $this->checkout = $checkout;
    }

    public function onLogin(InteractiveLoginEvent $event)
    {
        $user = $this->security->getUser();

        try {
            $order = $this->checkout->getOrderDraft();
        } catch (BackendUnavailable $e) {
            return;
        } catch (CartIsEmpty $e) {
            return;
        }

        $customer = $order->getCustomer();

        if ($customer === null
            || $customer->getId() !== $user->getCustomerId()
            || $customer->getUserId() !== $user->getUserId()) {
            try {
                $this->checkout->setCustomer($this->security->getCustomer(), $user);
            } catch (Exception $e) {}
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onLogin'
        ];
    }
}