<?php

namespace Paloma\ShopBundle\Controller\Api;

use Paloma\Shop\Customers\AddressUpdate;
use Paloma\Shop\Customers\CustomerDraft;
use Paloma\Shop\Customers\CustomersInterface;
use Paloma\Shop\Customers\CustomerUpdate;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\InvalidConfirmationToken;
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

    public function updateAddress(CustomersInterface $customers, PalomaSerializer $serializer, Request $request)
    {
        /** @var AddressUpdate $update */
        $update = $serializer->deserialize($request->getContent(), AddressUpdate::class);

        try {

            $address = $customers->updateAddress($update);

            return $serializer->toJsonResponse($address);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), 400);
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        }
    }

    public function confirmEmailAddress(CustomersInterface $customers, Request $request)
    {
        $token = $request->get('token');

        if (!$token) {
            return new Response('Parameter `token` missing', 400);
        }

        try {

            $customers->confirmEmailAddress($token);

            return new Response(null, '204');

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidConfirmationToken $e) {
            return new Response('Invalid confirmation token', 400);
        }
    }

    public function existsCustomerByEmailAddress(CustomersInterface $customers, PalomaSerializer $serializer, Request $request)
    {
        $emailAddress = $request->get('emailAddress');

        if (!$emailAddress) {
            return new Response('Parameter `emailAddress` missing', 400);
        }

        try {

            $exists = $customers->existsCustomerByEmailAddress($emailAddress);

            return $serializer->toJsonResponse(['exists' => $exists]);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        }
    }
}