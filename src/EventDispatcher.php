<?php

declare(strict_types=1);

namespace SSEventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

use function call_user_func;

class EventDispatcher implements EventDispatcherInterface
{
    private ListenerProviderInterface $provider;

    public function __construct(ListenerProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function dispatch(object $event)
    {
        if ($event->isPropagationStopped()) {
            return $event;
        }

        foreach ($this->provider->getListenersForEvent($event) as $listener) {
            call_user_func($listener, $event);

            if ($event->isPropagationStopped()) {
                break;
            }
        }

        return $event;
    }
}
