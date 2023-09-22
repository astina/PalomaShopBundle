<?php

namespace Paloma\ShopBundle\Controller\Api;

use Paloma\Shop\Customers\AddressUpdate;
use Paloma\Shop\Customers\CustomerDraft;
use Paloma\Shop\Customers\CustomersInterface;
use Paloma\Shop\Customers\CustomerUpdate;
use Paloma\Shop\Customers\CustomerUserDraft;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\CustomerNotFound;
use Paloma\Shop\Error\CustomerUserNotFound;
use Paloma\Shop\Error\InvalidConfirmationToken;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\NotAuthenticated;
use Paloma\Shop\Security\PalomaSecurityInterface;
use Paloma\ShopBundle\Serializer\PalomaSerializer;
use Paloma\ShopBundle\Serializer\SerializationConstants;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            return new JsonResponse(null, $e->getHttpStatus());
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        }
    }

    public function register(CustomersInterface $customers, PalomaSerializer $serializer, PalomaSecurityInterface $security, Request $request)
    {
        /** @var CustomerDraft $draft */
        $draft = $serializer->deserialize($request->getContent(), CustomerDraft::class);

        if ($draft->getLocale() == null) {
            $draft = $draft->withLocale($request->getLocale());
        }

        try {

            $user = $customers->registerCustomer($draft);

            $security->setUser($user);

            return $serializer->toJsonResponse($security->getCustomer(), SerializationConstants::OPTIONS_CUSTOMER);

        } catch (BackendUnavailable $e) {
            return new JsonResponse(null, $e->getHttpStatus());
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), ['status' => 400]);
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
            return new JsonResponse(null, $e->getHttpStatus());
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), ['status' => 400]);
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
            return new JsonResponse(null, $e->getHttpStatus());
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), ['status' => 400]);
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        }
    }

    public function updateEmailAddress(CustomersInterface $customers, PalomaSerializer $serializer, Request $request)
    {
        $data = $serializer->toArray($request->getContent());

        $emailAddress = $data['emailAddress'];

        if (!$emailAddress) {
            return new Response('Parameter `emailAddress` missing', 400);
        }

        try {

            $customer = $customers->updateEmailAddress($emailAddress);

            return $serializer->toJsonResponse($customer);

        } catch (BackendUnavailable $e) {
            return new JsonResponse(null, $e->getHttpStatus());
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), ['status' => 400]);
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

            return new JsonResponse(null, '204');

        } catch (BackendUnavailable $e) {
            return new JsonResponse(null, $e->getHttpStatus());
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

            $sessionKey = 'paloma.customer.existsCustomerByEmailAddress.failed_attempts';
            if ($exists) {
                $request->getSession()->remove($sessionKey);

                // make crawling for valid email addresses a little slower
            } else {
                $failedAttempts = $request->getSession()->get($sessionKey, 0);
                if ($failedAttempts > 2) {
                    // Sleep for max 10 seconds
                    usleep(min(10, $failedAttempts * 0.2) * 1000000);
                }
                $request->getSession()->set($sessionKey, $failedAttempts + 1);
            }

            return $serializer->toJsonResponse(['exists' => $exists]);

        } catch (BackendUnavailable $e) {
            return new JsonResponse(null, $e->getHttpStatus());
        }
    }

    public function listPaymentInstruments(CustomersInterface $customers, PalomaSerializer $serializer)
    {
        try {
            $paymentInstruments = $customers->listPaymentInstruments();

            return $serializer->toJsonResponse($paymentInstruments);

        } catch (BackendUnavailable $e) {
            return new JsonResponse(null, $e->getHttpStatus());
        }
    }

    public function deletePaymentInstrument(CustomersInterface $customers, Request $request)
    {
        $paymentInstrumentId = $request->get('id');

        try {

            $customers->deletePaymentInstrument($paymentInstrumentId);

            return new Response(null, 202);

        } catch (BackendUnavailable $e) {
            return new JsonResponse(null, $e->getHttpStatus());
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        }
    }

    public function listUsers(CustomersInterface $customers, PalomaSerializer $serializer, Request $request): JsonResponse|Response
    {
        $locale = $request->get('locale');

        try {

            $users = $customers->listUsers($locale);

            return $serializer->toJsonResponse($users);

        } catch (BackendUnavailable $e) {
            return new JsonResponse(null, $e->getHttpStatus());
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        } catch (CustomerNotFound $e) {
            return new Response('Customer not found', 404);
        }
    }

    public function getUser(CustomersInterface $customers, PalomaSerializer $serializer, Request $request): JsonResponse|Response
    {
        $userId = $request->get('id');

        try {

            $users = $customers->getUser($userId);

            return $serializer->toJsonResponse($users);

        } catch (BackendUnavailable $e) {
            return new JsonResponse(null, $e->getHttpStatus());
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        } catch (CustomerNotFound $e) {
            return new Response('Customer not found', 404);
        } catch (CustomerUserNotFound $e) {
            return new Response('User not found', 404);
        }
    }

    public function createUser(CustomersInterface $customers, PalomaSerializer $serializer, Request $request): JsonResponse|Response
    {
        /** @var CustomerUserDraft $draft */
        $draft = $serializer->deserialize($request->getContent(), CustomerUserDraft::class);

        try {

            $users = $customers->createUser($draft);

            return $serializer->toJsonResponse($users);

        } catch (BackendUnavailable $e) {
            return new JsonResponse(null, $e->getHttpStatus());
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        } catch (CustomerNotFound $e) {
            return new Response('Customer not found', 404);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), ['status' => 400]);
        }
    }

    public function updateUser(CustomersInterface $customers, PalomaSerializer $serializer, Request $request): JsonResponse|Response
    {
        /** @var CustomerUserDraft $draft */
        $draft = $serializer->deserialize($request->getContent(), CustomerUserDraft::class);
        $userId = $request->get('id');

        try {

            $users = $customers->updateUser($userId, $draft);

            return $serializer->toJsonResponse($users);

        } catch (BackendUnavailable $e) {
            return new JsonResponse(null, $e->getHttpStatus());
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        } catch (CustomerNotFound $e) {
            return new Response('Customer not found', 404);
        } catch (CustomerUserNotFound $e) {
            return new Response('User not found', 404);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), ['status' => 400]);
        }
    }

    public function deleteUser(CustomersInterface $customers, Request $request): Response
    {
        $userId = $request->get('id');

        try {

            $customers->deleteUser($userId);

            return new Response(null, 202);

        } catch (BackendUnavailable $e) {
            return new Response(null, $e->getHttpStatus());
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        } catch (CustomerNotFound $e) {
            return new Response('Customer not found', 404);
        }
    }
}