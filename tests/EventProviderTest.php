<?php

declare(strict_types=1);

namespace SSEventDispatcher\UnitTest;

use Generator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use SSEventDispatcher\EventProvider;
use stdClass;

class EventProviderTest extends TestCase
{
    private EventProvider $provider;
    private stdClass $event;

    protected function setUp(): void
    {
        $this->event = new stdClass();
        $this->provider = new EventProvider();
    }

    public function testGetListenerForEvent(): void
    {
        /** @var MockObject|ListenerTester $listener */
        $listener = $this->getListener(fn(object $event) => $event->weight = 2, 1);

        $this->provider->addListener($listener);

        /** @var Generator $listeners */
        $listeners = $this->provider->getListenersForEvent($this->event);
        
        $listeners->current()($this->event);

        $listeners->next();

        $this->assertFalse($listeners->valid());
        $this->assertEquals(2, $this->event->weight);
    }

    public function testListenersOrder(): void
    {
        /** @var MockObject|ListenerTester $listener */
        $listener = $this->getListener(fn(object $event) => $event->weight = 2, 2);

        $this->provider->addListener($listener);
        
        /** @var MockObject|ListenerTester $listener */
        $listener2 = $this->getListener(fn(object $event) => $event->weight = 3, 1);

        $this->provider->addListener($listener2);

        /** @var Generator $listeners */
        $listeners = $this->provider->getListenersForEvent($this->event);

        $listeners->current()($this->event);
        $this->assertEquals(3, $this->event->weight);

        $listeners->next();
        $listeners->current()($this->event);
        $this->assertEquals(2, $this->event->weight);
        
        $listeners->next();
        
        $this->assertFalse($listeners->valid());
    }

    public function testListenerNotFound(): void
    {
        $result = $this->provider->getListenersForEvent($this->event);

        $this->assertFalse($result->valid());
    }

    private function getListener(callable $callable, int $order): MockObject|ListenerTester
    {
        /** @var MockObject|EventListener $listener */
        $listener = $this->createMock(ListenerTester::class);

        $listener
            ->expects($this->once())
            ->method('getName')
            ->willReturn(stdClass::class);

        $listener
            ->expects($this->once())
            ->method('getPriority')
            ->willReturn($order);

        $listener
            ->expects($this->once())
            ->method('getCallable')
            ->willReturn($callable);
   
        return $listener;
    }
}
