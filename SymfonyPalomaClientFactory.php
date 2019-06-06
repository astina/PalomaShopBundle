<?php

namespace Paloma\ShopBundle;

use Paloma\Shop\PalomaClientFactory;
use Paloma\Shop\PalomaProfiler;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SymfonyPalomaClientFactory extends PalomaClientFactory
{
    private $channel = null;

    private $locale = null;

    private $traceId;

    public function __construct(SessionInterface $session,
                                array $options,
                                PalomaProfiler $profiler = null)
    {
        $options['session'] = $session;
        $options['profiler'] = $profiler;

        parent::__construct($options);
    }

    public function create($channel = null, $locale = null, $traceId = null)
    {
        return parent::create(
            $channel ?? $this->channel,
            $locale ?? $this->locale,
            $traceId ?? $this->traceId
        );
    }

    public function setContext(string $channel, string $locale, string $traceId)
    {
        $this->channel = $channel;
        $this->locale = $locale;
        $this->traceId = $traceId;
    }
}