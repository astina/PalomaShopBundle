<?php

namespace Paloma\ShopBundle;

use Paloma\Shop\PalomaClientFactory;
use Paloma\Shop\PalomaConfigInterface;
use Paloma\Shop\PalomaProfiler;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class SymfonyPalomaClientFactory extends PalomaClientFactory
{
    /**
     * @var ChannelResolverInterface
     */
    private $channelResolver;

    /**
     * @var PalomaConfigInterface
     */
    private $config;

    private $channel = null;

    private $locale = null;

    private $traceId;

    public function __construct(ChannelResolverInterface $channelResolver,
                                PalomaConfigInterface $config,
                                SessionInterface $session,
                                array $options,
                                PalomaProfiler $profiler = null)
    {
        $options['session'] = $session;
        $options['profiler'] = $profiler;

        parent::__construct($options);

        $this->channelResolver = $channelResolver;
        $this->config = $config;
    }

    public function create($channel = null, $locale = null, $traceId = null)
    {
        return parent::create(
            $channel ?? $this->channel,
            $locale ?? $this->locale,
            $traceId ?? $this->traceId
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $request = $event->getRequest();

        $this->channel = $this->channelResolver->resolveChannel($request);

        $this->locale = $request->attributes->get('_locale');

        $this->traceId = $request->headers->get(
            'x-paloma-trace-id',
            $request->headers->get(
                'x-astina-trace-id',
                $this->createTraceId()));

        $request->attributes->set('paloma.channel', $this->channel);
    }

    private function createTraceId(): string
    {
        // TODO get first part from session id
        return substr(uniqid(), 0, 8);
    }
}