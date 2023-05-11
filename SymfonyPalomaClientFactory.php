<?php

namespace Paloma\ShopBundle;

use Paloma\Shop\PalomaClientFactory;
use Paloma\Shop\PalomaProfiler;
use Symfony\Component\HttpFoundation\RequestStack;

class SymfonyPalomaClientFactory extends PalomaClientFactory
{
    private $channel = null;

    private $locale = null;

    private $traceId;

    public function __construct(RequestStack $requestStack,
                                array $options,
                                PalomaProfiler $profiler = null)
    {
        $options['request_stack'] = $requestStack;
        $options['profiler'] = $profiler;
        $options['trace_id'] = null;

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