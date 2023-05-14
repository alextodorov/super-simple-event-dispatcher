<?php

declare(strict_types=1);

namespace SSEventDispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;
use Throwable;

use function ksort;

class EventProvider implements ListenerProviderInterface
{
    private array $listeners = [];

    /**
     * @param  object $event
     * @return iterable[callable]
     */
    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->listeners[$event::class] as $data) {
            foreach ($data as $listener) {
                yield $listener;
            }
        }
    }

    public function addListener(object | callable $listener, string $name, int $priority = 1)
    {
        $this->listeners[$name][$priority][] = $listener;

        ksort($this->listeners[$name]);
    }

    public function addSubsciber(EventSubscriberInterface $subscriber)
    {
        try {
            foreach ($subscriber->getSubscribeEvents() as $data) {
                $this->addListener($data['listener'], $data['name'], $data['priority']);
            }
        } catch (Throwable) {
            throw new InvalidSubscriber(
                'Please provide a valid subscriber. The Array with keys listener, name, priority'
            );
        }
    }
}
