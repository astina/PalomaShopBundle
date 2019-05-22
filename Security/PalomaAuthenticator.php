<?php

namespace Paloma\ShopBundle\Security;

use Paloma\Shop\Customers\CustomersInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\BadCredentials;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class PalomaAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    const ROUTE_LOGIN_FORM = 'paloma_security_login';

    const ROUTE_API_AUTH = 'paloma_api_user_authenticate';

    private $urlGenerator;

    private $csrfTokenManager;

    private $customers;

    public function __construct(UrlGeneratorInterface $urlGenerator,
                                CsrfTokenManagerInterface $csrfTokenManager,
                                CustomersInterface $customers)
    {
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->customers = $customers;
    }

    public function supports(Request $request)
    {
        return $this->isLoginFormRequest($request)
            || $this->isApiAuthRequest($request);
    }

    public function getCredentials(Request $request)
    {
        if ($this->isLoginFormRequest($request)) {
            return $this->getLoginFormCredentials($request);
        }

        return $this->getApiAuthCredentials($request);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

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

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($this->isApiAuthRequest($request)) {
            return new JsonResponse(null, 204);
        }

        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('index'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($this->isApiAuthRequest($request)) {
            return new JsonResponse(['message' => 'Bad credentials'], 403);
        }

        return parent::onAuthenticationFailure($request, $exception);
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::ROUTE_LOGIN_FORM);
    }

    private function isLoginFormRequest(Request $request)
    {
        return self::ROUTE_LOGIN_FORM === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    private function isApiAuthRequest(Request $request)
    {
        return self::ROUTE_API_AUTH === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    private function getLoginFormCredentials(Request $request): array
    {
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    private function getApiAuthCredentials(Request $request): array
    {
        $data = json_decode($request->getContent(), true);

        return [
            'username' => $data['username'],
            'password' => $data['password'],
            'csrf_token' => $request->headers->get('x-csrf-token'),
        ];
    }
}