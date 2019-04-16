<?php

namespace Paloma\ShopBundle\Controller;

use DateTime;
use Paloma\Shop\Checkout\CheckoutInterface;
use Paloma\Shop\Checkout\PaymentInitParameters;
use Paloma\Shop\Common\Address;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\InvalidCouponCode;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\InvalidShippingTargetDate;
use Paloma\Shop\Error\NonElectronicPaymentMethod;
use Paloma\Shop\Error\OrderNotReadyForPayment;
use Paloma\Shop\Error\OrderNotReadyForPurchase;
use Paloma\Shop\Error\UnknownPaymentMethod;
use Paloma\Shop\Error\UnknownShippingMethod;
use Paloma\ShopBundle\PalomaSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckoutResource
{
    public function setAddresses(CheckoutInterface $checkout, PalomaSerializer $serializer, Request $request)
    {
        $data = $serializer->toArray($request->getContent());

        $billingAddress = isset($data['billing']) ? Address::ofData($data['billing']) : null;
        $shippingAddress = isset($data['shipping']) ? Address::ofData($data['shipping']) : null;

        if ($billingAddress === null && $shippingAddress === null) {
            return new Response('Parameter `billing` or `shipping` required', 400);
        }

        if ($billingAddress === null) {
            $billingAddress = $shippingAddress;
        }

        try {

            $order = $checkout->setAddresses($billingAddress, $shippingAddress);

            return $serializer->toJsonResponse($order);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), 400);
        }
    }

    public function listShippingMethods(CheckoutInterface $checkout, PalomaSerializer $serializer)
    {
        try {

            $methods = $checkout->getShippingMethods();

            return $serializer->toJsonResponse($methods);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        }
    }

    public function getShippingMethodOptions(CheckoutInterface $checkout, PalomaSerializer $serializer, Request $request)
    {
        $method = $request->get('method');
        $from = $request->get('from');
        $until = $request->get('until');

        if (!$method) {
            return new Response('Parameter `method` missing', 400);
        }

        try {

            $options = $checkout->getShippingMethodOptions(
                $method,
                $from ? DateTime::createFromFormat('Y-m-d', $from) : null,
                $until ? DateTime::createFromFormat('Y-m-d', $until) : null
            );

            return $serializer->toJsonResponse($options);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), 400);
        } catch (UnknownShippingMethod $e) {
            return new Response('Unknown shipping method', 404);
        }
    }

    public function setShippingMethod(CheckoutInterface $checkout, PalomaSerializer $serializer, Request $request)
    {
        $data = $serializer->toArray($request->getContent());

        $method = $data['method'] ?? null;
        $targetDate = $data['targetDate'] ?? null;

        if (!$method) {
            return new Response('Parameter `method` missing', 400);
        }

        try {

            $order = $checkout->setShippingMethod(
                $method,
                $targetDate ? DateTime::createFromFormat('Y-m-d', $targetDate) : null
            );

            return $serializer->toJsonResponse($order);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidShippingTargetDate $e) {
            return new Response('Invalid shipping target date', 400);
        } catch (UnknownShippingMethod $e) {
            return new Response('Unknown shipping method', 404);
        }
    }

    public function listPaymentMethods(CheckoutInterface $checkout, PalomaSerializer $serializer)
    {
        try {

            $methods = $checkout->getPaymentMethods();

            return $serializer->toJsonResponse($methods);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        }
    }

    public function setPaymentMethod(CheckoutInterface $checkout, PalomaSerializer $serializer, Request $request)
    {
        $data = $serializer->toArray($request->getContent());

        $method = $data['method'] ?? null;

        if (!$method) {
            return new Response('Parameter `method` missing', 400);
        }

        try {

            $order = $checkout->setPaymentMethod($method);

            return $serializer->toJsonResponse($order);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (UnknownPaymentMethod $e) {
            return new Response('Unknown shipping method', 404);
        }
    }

    public function addCouponCode(CheckoutInterface $checkout, PalomaSerializer $serializer, Request $request)
    {
        $data = $serializer->toArray($request->getContent());

        $code = $data['code'] ?? null;

        if (!$code) {
            return new Response('Parameter `code` missing', 400);
        }

        try {

            $order = $checkout->addCouponCode($code);

            return $serializer->toJsonResponse($order);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidCouponCode $e) {
            return $serializer->toJsonResponse($e, 400);
        }

    }

    public function removeCouponCode(CheckoutInterface $checkout, PalomaSerializer $serializer, Request $request)
    {
        $data = $serializer->toArray($request->getContent());

        $code = $data['code'] ?? null;

        if (!$code) {
            return new Response('Parameter `code` missing', 400);
        }

        try {

            $order = $checkout->removeCouponCode($code);

            return $serializer->toJsonResponse($order);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        }
    }

    public function initializePayment(CheckoutInterface $checkout, PalomaSerializer $serializer, Request $request)
    {
        $params = $serializer->deserialize($request->getContent(), PaymentInitParameters::class);

        try {

            $payment = $checkout->initializePayment($params);

            return $serializer->toJsonResponse($payment);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (NonElectronicPaymentMethod $e) {
            return new Response('The selected payment method is not electronic', 400);
        } catch (OrderNotReadyForPayment $e) {
            return new Response('Order not ready for payment', 400);
        }
    }

    public function purchase(CheckoutInterface $checkout, PalomaSerializer $serializer)
    {
        try {

            $purchase = $checkout->purchase();

            return $serializer->toJsonResponse($purchase);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (OrderNotReadyForPurchase $e) {
            return new Response('Order not ready for purchase', 400);
        }
    }
}