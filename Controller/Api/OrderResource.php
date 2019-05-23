<?php

namespace Paloma\ShopBundle\Controller\Api;

use Paloma\Shop\Customers\CustomersInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\NotAuthenticated;
use Paloma\Shop\Error\OrderNotFound;
use Paloma\ShopBundle\Serializer\PalomaSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderResource
{
    public function list(CustomersInterface $customers, PalomaSerializer $serializer, Request $request)
    {
        $page = max(0, (int)$request->get('page', 0));
        $size = min(10, (int)$request->get('size', 5));
        $orderDesc = (bool)$request->get('orderDesc', true);

        try {

            $orders = $customers->getOrders($page, $size, $orderDesc);

            return $serializer->toJsonResponse($orders);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), ['status' => 400]);
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        }
    }

    public function get(CustomersInterface $customers, PalomaSerializer $serializer, Request $request)
    {
        $orderNumber = (string)$request->get('orderNumber');

        if (!$orderNumber) {
            return new Response('Parameter `orderNumber` missing', 400);
        }

        try {

            $order = $customers->getOrder($orderNumber);

            return $serializer->toJsonResponse($order);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        } catch (OrderNotFound $e) {
            return new Response('Order not found', 404);
        }
    }

    public function latest(CustomersInterface $customers, PalomaSerializer $serializer)
    {
        try {

            $orders = $customers->getOrders(0, 1, true);

            if (count($orders->getContent()) === 0) {
                return new Response(null, 204);
            }

            $order = $orders->getContent()[0];

            return $serializer->toJsonResponse($order);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), ['status' => 400]);
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        }
    }
}