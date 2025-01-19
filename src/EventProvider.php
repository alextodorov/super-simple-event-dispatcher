<?php

declare(strict_types=1);

namespace SSEventDispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;

use function ksort;

class EventProvider implements ListenerProviderInterface
{
    private array $listeners = [];

    public function getListenersForEvent(object $event): iterable
    {
        if (!isset($this->listeners[$event::class])) {
            return [];
        }
        
        ksort($this->listeners[$event::class]);
        foreach ($this->listeners[$event::class] as $data) {
            foreach ($data as $listener) {
                yield $listener;
            }
        }
    }

    public function addListener(EventListenerable $listener): void
    {
        $this->listeners[$listener->getName()][$listener->getPriority()][] = $listener->getCallable();
    }
}
