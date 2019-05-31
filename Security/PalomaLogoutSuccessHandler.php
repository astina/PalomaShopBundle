<?php

namespace Paloma\ShopBundle\Security;

use Exception;
use Paloma\Shop\Checkout\CheckoutInterface;
use Paloma\Shop\Checkout\GuestCustomer;
use Paloma\Shop\Error\BackendUnavailable;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class PalomaLogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    private $delegate;

    private $checkout;

    private $log;

    public function __construct(CheckoutInterface $checkout, LoggerInterface $log, HttpUtils $httpUtils, string $targetUrl = '/')
    {
        $this->delegate = new DefaultLogoutSuccessHandler($httpUtils, $targetUrl);
        $this->checkout = $checkout;
        $this->log = $log;
    }

    public function onLogoutSuccess(Request $request): Response
    {
        try {

            $this->checkout->setCustomer(new GuestCustomer(
                null,
                $request->getLocale()
            ));

        } catch (BackendUnavailable $e) {
        } catch (Exception $e) {
            $this->log->error('Error in PalomaLogoutSuccessHandler: ' . $e->getMessage());
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(null, 204);
        }

        return $this->delegate->onLogoutSuccess($request);
    }
}