<?php

namespace Paloma\ShopBundle\EventListener;

use Paloma\Shop\Checkout\CheckoutInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\CartIsEmpty;
use Paloma\Shop\Security\UserProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class PalomaAuthenticationListener implements EventSubscriberInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var CheckoutInterface
     */
    private $checkout;

    public function __construct(UserProviderInterface $userProvider, CheckoutInterface $checkout)
    {
        $this->userProvider = $userProvider;
        $this->checkout = $checkout;
    }

    public function onLogin(InteractiveLoginEvent $event)
    {
        $user = $this->userProvider->getUser();

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
                $this->checkout->setCustomer($this->userProvider->getCustomer(), $user);
            } catch (BackendUnavailable $e) {}
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onLogin'
        ];
    }
}