<?php

namespace Paloma\ShopBundle\EventListener;

use Paloma\ShopBundle\ChannelResolverInterface;
use Paloma\ShopBundle\SymfonyPalomaClientFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Tries to determine the current channel and locale based on the request.
 */
class PalomaContextListener
{
    private $channelResolver;

    private $clientFactory;

    private $router;

    public function __construct(ChannelResolverInterface $channelResolver,
                                SymfonyPalomaClientFactory $clientFactory,
                                RouterInterface $router)
    {
        $this->channelResolver = $channelResolver;
        $this->clientFactory = $clientFactory;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $request = $event->getRequest();

        $channel = $this->channelResolver->resolveChannel($request);

        $locale = $request->attributes->get('_locale', 'en'); // TODO default locale by channel

        $traceId = $request->headers->get(
            'x-paloma-trace-id',
            $request->headers->get('x-astina-trace-id',
                $this->createTraceId($request)));

        $this->clientFactory->setContext(
            $channel,
            $locale,
            $traceId
        );

        $request->attributes->set('paloma.channel', $channel);

        $this->router->getContext()->setParameter('paloma_channel', $channel);
        $this->router->getContext()->setParameter('paloma_locale', $locale);
    }

    private function createTraceId(Request $request): string
    {
        $session = $request->getSession();
        if ($session->isStarted()) {
            return substr($session->getId(), 0, 4) . substr(uniqid(), 0, 4);
        }

        return substr(uniqid(), 0, 8);
    }
}