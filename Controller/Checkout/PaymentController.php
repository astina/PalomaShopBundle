<?php

namespace Paloma\ShopBundle\Controller\Checkout;

use Exception;
use Paloma\Shop\Checkout\CheckoutInterface;
use Paloma\Shop\Checkout\PaymentInitParameters;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\OrderNotReadyForPurchase;
use Paloma\ShopBundle\Controller\AbstractPalomaController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaymentController extends AbstractPalomaController
{
    public function start(CheckoutInterface $checkout, Request $request, RouterInterface $router, TranslatorInterface $translator, LoggerInterface $log)
    {
        try {

            $payment = $checkout->initializePayment(new PaymentInitParameters(
                $router->generate('paloma_checkout_payment_success', [], RouterInterface::ABSOLUTE_URL),
                $router->generate('paloma_checkout_payment_cancel', [], RouterInterface::ABSOLUTE_URL),
                $router->generate('paloma_checkout_payment_error', [], RouterInterface::ABSOLUTE_URL)
            ));

            if (($paymentUrl = $payment->getPaymentUrl()) !== null) {
                return $this->redirect($paymentUrl);
            }

            $log->error('No payment URL provided');

        } catch (Exception $e) {
            $log->error('Unable to initialize payment', ['error' => $e->getMessage()]);
        }

        /** @var Session $session */
        $session = $request->getSession();
        $session->getFlashBag()->add('paloma.checkout_errors', $translator->trans('payment.error.general'));

        return $this->redirectToRoute('paloma_checkout_start');
    }

    public function success(CheckoutInterface $checkout, Request $request, TranslatorInterface $translator, LoggerInterface $log)
    {
        if ($request->getSession()->isStarted()) {
            return $this->complete($checkout, $request, $translator, $log);
        }

        // Redirect again to ensure that the browser sends our session cookie

        return $this->redirectToRoute('paloma_checkout_payment_complete');
    }

    public function complete(CheckoutInterface $checkout, Request $request, TranslatorInterface $translator, LoggerInterface $log)
    {
        try {
            $purchase = $checkout->purchase();
        } catch (BackendUnavailable|OrderNotReadyForPurchase $e) {

            $log->error('Error during order purchase', ['error' => $e]);

            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->add('paloma.checkout_errors', $translator->trans('payment.error.general'));

            return $this->redirectToRoute('paloma_checkout_start');
        }

        $request->getSession()->set('paloma-order-number', $purchase->getOrderNumber());

        return $this->redirectToRoute('paloma_checkout_success');
    }

    public function cancel()
    {
        return $this->redirectToRoute('paloma_checkout_state', ['state' => 'confirm']);
    }

    public function error(Request $request, TranslatorInterface $translator)
    {
        // TODO make configurable or add more params

        $errorMessageParams = [
            'errorDetail',
            'errorMessage',
        ];

        $errorCodeParams = [
            'code',
            'errorCode',
        ];

        $message = 'Payment failed';
        $code = 'general';

        foreach ($errorMessageParams as $param) {
            if (($message = $request->get($param)) !== null) {
                break;
            }
        }
        foreach ($errorCodeParams as $param) {
            if (($code = $request->get($param)) !== null) {
                break;
            }
        }

        /** @var Session $session */
        $session = $request->getSession();

        $key = 'payment.error.' . $code;
        if (($translatedMessage = $translator->trans($key)) === $key) {
            $translatedMessage = $message;
        } else {
            $translatedMessage = $translatedMessage . ($message ? ' (' . $message . ')' : '');
        }

        $session->getFlashBag()->add('paloma.checkout_errors', $translatedMessage);

        return $this->redirectToRoute('paloma_checkout_state', ['state' => 'confirm']);
    }
}