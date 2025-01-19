<?php

declare(strict_types=1);

namespace SSEventDispatcher\UnitTest;

use Generator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use SSEventDispatcher\EventProvider;
use SSEventDispatcher\EventListener;
use stdClass;

class EventProviderTest extends TestCase
{
    private EventProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new EventProvider();
    }

    public function testAddGetListener(): void
    {
        $event = new stdClass();

        /** @var MockObject|EventListener $listener */
        $listener = $this->getListener(fn(object $event) => $event->weight = 2, 1);

        $this->provider->addListener($listener);

        /** @var Generator $listeners */
        $listeners = $this->provider->getListenersForEvent($event);

        $this->assertTrue($listeners->valid());

        $this->assertIsCallable($listeners->current());
        
        $listeners->current()($event);

        $listeners->next();

        $this->assertFalse($listeners->valid());
        $this->assertEquals(2, $event->weight);
    }

    public function testListenersOrder(): void
    {
        $event = new stdClass();

        /** @var MockObject|EventListener $listener */
        $listener = $this->getListener(fn(object $event) => $event->weight = 2, 2);

        $this->provider->addListener($listener);
        
        /** @var MockObject|EventListener $listener */
        $listener2 = $this->getListener(fn(object $event) => $event->weight = 3, 1);

        $this->provider->addListener($listener2);

        /** @var Generator $listeners */
        $listeners = $this->provider->getListenersForEvent($event);

        $this->assertTrue($listeners->valid());
        $this->assertIsCallable($listeners->current());

        $listeners->current()($event);
        $this->assertEquals(3, $event->weight);

        $listeners->next();
        
        $this->assertIsCallable($listeners->current());

        $listeners->current()($event);
        $this->assertEquals(2, $event->weight);
        
        $listeners->next();
        
        $this->assertFalse($listeners->valid());
    }

    public function testListenerNotFound(): void
    {
        $event = new stdClass();
        $result = $this->provider->getListenersForEvent($event);

        $this->assertFalse($result->valid());
    }

    private function getListener(callable $callable, int $order): MockObject|EventListener
    {
        /** @var MockObject|EventListener $listener */
        $listener = $this->createMock(EventListener::class);

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