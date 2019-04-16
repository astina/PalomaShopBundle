<?php

namespace Paloma\ShopBundle\Controller;

use Paloma\Shop\Customers\CustomerDraft;
use Paloma\Shop\Customers\CustomersInterface;
use Paloma\Shop\Customers\CustomerUpdate;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\NotAuthenticated;
use Paloma\ShopBundle\PalomaSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerResource
{
    public function get(CustomersInterface $customers, PalomaSerializer $serializer)
    {
        try {

            $customer = $customers->getCustomer();

            return $serializer->toJsonResponse($customer);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        }
    }

    public function register(CustomersInterface $customers, PalomaSerializer $serializer, Request $request)
    {
        /** @var CustomerDraft $draft */
        $draft = $serializer->deserialize($request->getContent(), CustomerDraft::class);

        if ($draft->getLocale() == null) {
            $draft = $draft->withLocale($request->getLocale());
        }

        try {

            $customer = $customers->registerCustomer($draft);

            return $serializer->toJsonResponse($customer);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), 400);
        }
    }

    public function update(CustomersInterface $customers, PalomaSerializer $serializer, Request $request)
    {
        /** @var CustomerUpdate $update */
        $update = $serializer->deserialize($request->getContent(), CustomerUpdate::class);

        try {

            $customer = $customers->updateCustomer($update);

            return $serializer->toJsonResponse($customer);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), 400);
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        }
    }
}