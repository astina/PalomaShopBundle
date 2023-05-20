<?php

namespace Paloma\ShopBundle\Security;

use Paloma\Shop\Customers\CustomersInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\BadCredentials;
use Paloma\Shop\Security\PalomaSecurityInterface;
use Paloma\ShopBundle\Serializer\PalomaSerializer;
use Paloma\ShopBundle\Serializer\SerializationConstants;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class PalomaAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    const ROUTE_LOGIN_FORM = 'paloma_security_login';

    const ROUTE_API_AUTH = 'paloma_api_user_authenticate';

    private UrlGeneratorInterface $urlGenerator;

    private CsrfTokenManagerInterface $csrfTokenManager;

    private CustomersInterface $customers;

    private PalomaSecurityInterface $security;

    private PalomaSerializer $serializer;

    public function __construct(UrlGeneratorInterface $urlGenerator,
                                CsrfTokenManagerInterface $csrfTokenManager,
                                CustomersInterface $customers,
                                PalomaSecurityInterface $security,
                                PalomaSerializer $serializer)
    {
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->customers = $customers;
        $this->security = $security;
        $this->serializer = $serializer;
    }
    public function authenticate(Request $request): Passport
    {
        $credentials = $this->getCredentials($request);

        $passport = new SelfValidatingPassport(
            new UserBadge($credentials['username']),
            [
                new CustomCredentials([$this, 'checkCredentials'], $credentials['password']),
                new CsrfTokenBadge('authenticate', $credentials['csrf_token']),
            ]
        );

        if (isset($credentials['remember_me'])) {
            $passport->addBadge((new RememberMeBadge())->enable());
        }

        return $passport;
    }

    public function supports(Request $request): bool
    {
        return $this->isLoginFormRequest($request)
            || $this->isApiAuthRequest($request);
    }

    private function getCredentials(Request $request): array
    {
        if ($this->isLoginFormRequest($request)) {
            return $this->getLoginFormCredentials($request);
        }

        return $this->getApiAuthCredentials($request);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        try {

            $userDetails = $this->customers->authenticate(
                $user->getUserIdentifier(),
                $credentials
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

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $firewallName): ?Response
    {
        if ($this->isApiAuthRequest($request)) {

            $user = $this->security->getUser();

            return $this->serializer->toJsonResponse($user, SerializationConstants::OPTIONS_USER);
        }

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('paloma_catalog_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($this->isApiAuthRequest($request)) {
            return new JsonResponse(['message' => 'Bad credentials'], 403);
        }

        return parent::onAuthenticationFailure($request, $exception);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::ROUTE_LOGIN_FORM);
    }

    private function isLoginFormRequest(Request $request): bool
    {
        return self::ROUTE_LOGIN_FORM === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    private function isApiAuthRequest(Request $request): bool
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
            'remember_me' => $request->request->get('_remember_me'),
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