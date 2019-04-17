<?php

namespace Paloma\ShopBundle\Controller;

use Exception;
use Paloma\Shop\Customers\CustomersInterface;
use Paloma\Shop\Customers\PasswordReset;
use Paloma\Shop\Customers\PasswordUpdate;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\BadCredentials;
use Paloma\Shop\Error\InvalidConfirmationToken;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\NotAuthenticated;
use Paloma\ShopBundle\PalomaSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserResource
{
    public function authenticate(CustomersInterface $customers, PalomaSerializer $serializer, Request $request)
    {
        $username = null;
        $password = null;
        try {

            $credentials = $serializer->toArray($request->getContent());

            $username = $credentials['username'];
            $password = $credentials['password'];

        } catch (Exception $e) {}

        if (!$username || !$password) {
            return new Response('Expecting credentials as JSON: {"username": "...", "password": "...."}', 400);
        }

        try {

            $customers->authenticate($username, $password);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (BadCredentials $e) {
            return new Response('Bad credentials', 403);
        }
    }

    public function updatePassword(CustomersInterface $customers, PalomaSerializer $serializer, Request $request)
    {
        $update = $serializer->deserialize($request->getContent(), PasswordUpdate::class);

        try {

            $customers->updatePassword($update);

            return new Response(null, 204);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (BadCredentials $e) {
            return new Response('Bad credentials', 403);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), 400);
        } catch (NotAuthenticated $e) {
            return new Response('Unauthorized', 401);
        }
    }

    public function startPasswordReset(CustomersInterface $customers, PalomaSerializer $serializer, Request $request)
    {
        $emailAddress = null;

        try {

            $data = $serializer->toArray($request->getContent());

            $emailAddress = $data['emailAddress'];

        } catch (Exception $e) {}

        if (!$emailAddress) {
            return new Response('Parameter `emailAddress` missing', 400);
        }

        try {

            $customers->startPasswordReset($emailAddress);

            return new Response(null, 204);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidInput $e) {
            return new Response(null, 204);
        }
    }

    public function existsPasswordReset(CustomersInterface $customers, PalomaSerializer $serializer, Request $request)
    {
        $token = $request->get('token');

        if (!$token) {
            return new Response('Parameter `token` missing', 400);
        }

        try {

            $exists = $customers->existsPasswordResetToken($token);

            return $serializer->toJsonResponse(['exists' => $exists]);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        }
    }

    public function completePasswordReset(CustomersInterface $customers, PalomaSerializer $serializer, Request $request)
    {
        $reset = $serializer->deserialize($request->getContent(), PasswordReset::class);

        try {

            $customers->updatePasswordWithResetToken($reset);

            return new Response(null, 204);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidConfirmationToken $e) {
            return new Response('Invalid confirmation token', 400);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), 400);
        }
    }
}