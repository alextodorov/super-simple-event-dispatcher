<?php

declare(strict_types=1);

namespace SSEventDispatcher\UnitTest;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use SSEventDispatcher\EventDispatcher;
use SSEventDispatcher\EventProvider;

class EventDispatcherTest extends TestCase
{
    public function testDispatch()
    {
        $event = new class implements StoppableEventInterface {
            private bool $stoppable = false;

            public function isPropagationStopped(): bool
            {
                return $this->stoppable;
            }
        };

        $provider = $this->getProvider(
            $event,
            [ 
                function (object $event) {
                    return $event;
                }
            ]
        );

        $dispatcher = new EventDispatcher($provider);

        $this->assertInstanceOf(EventDispatcherInterface::class, $dispatcher);

        $this->assertInstanceOf(StoppableEventInterface::class, $dispatcher->dispatch($event));
    }

    public function testStoppableEvent()
    {
        $event = new class implements StoppableEventInterface {
            private bool $stoppable = false;
            
            public int $count = 0;

            public function isPropagationStopped(): bool
            {
                return $this->stoppable;
            }

            public function stopPropagation(): void
            {
                $this->stoppable = true;
            }
        };

        $provider = $this->getProvider(
            $event,
            [ 
                function (object $event) {
                    $event->stopPropagation();

                    $event->count++;

                    return $event;
                }
            ]
        );

        $dispatcher = new EventDispatcher($provider);

        $dispatcher->dispatch($event);

        $this->assertEquals(1, $event->count);

        $dispatcher->dispatch($event);

        $this->assertEquals(1, $event->count);
    }

    private function getProvider(object $event, array $listener): MockObject|ListenerProviderInterface
    {
        /** @var MockObject $provider */
        $provider = $this->createMock(EventProvider::class);

        $provider
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn($listener);

        return $provider;
    }
}
