<?php

namespace Paloma\ShopBundle\Security;

use Paloma\Shop\Customers\CustomersInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\BadCredentials;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Authenticator used when authenticating via HTTP JSON API
 */
class HttpJsonAuthenticator extends AbstractGuardAuthenticator
{
    const AUTH_ROUTE = 'paloma_api_user_authenticate';

    private $csrfTokenManager;

    private $customers;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager,
                                CustomersInterface $customers)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->customers = $customers;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response('Unauthorized', 401);
    }

    public function supports(Request $request)
    {
        return self::AUTH_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        return [
            'username' => $data['username'],
            'password' => $data['password'],
            'csrf_token' => $request->headers->get('x-csrf-token'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        // Actual user will be loaded when checking credentials

        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        try {

            $userDetails = $this->customers->authenticate(
                $credentials['username'],
                $credentials['password']
            );

            /** @var $user PalomaUser */
            $user->setDetails($userDetails);

            return true;
        } catch (BackendUnavailable $e) {
            throw new AuthenticationServiceException();
        } catch (BadCredentials $e) {
            return false;
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response('Bad credentials', 403);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }

    public function supportsRememberMe()
    {
        return false;
    }
}