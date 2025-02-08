<?php

declare(strict_types=1);

namespace SSEventDispatcher\UnitTest;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SSEventDispatcher\EventDispatcher;
use SSEventDispatcher\EventProvider;

class EventDispatcherTest extends TestCase
{
    private EventDispatcher $dispatcher;
    private EventTester $event;
    private MockObject|EventProvider $provider;

    protected function setUp(): void
    {
        $this->event = new EventTester();
        $this->provider = $this->createMock(EventProvider::class);
        $this->dispatcher = new EventDispatcher($this->provider);
    }

    public function testDispatchFunction(): void
    {
        $this->setUpProvider(
            [
                function (object $event) {
                    $event->increment();
                }
            ]
        );

        $this->dispatcher->dispatch($this->event);
        $this->assertEquals(1, $this->event->getCount());
    }

    public function testDispatchObject(): void
    {
        $object = new class {
            public function __invoke(object $event)
            {
                $event->increment();
            }
        };

        $this->setUpProvider([$object]);

        $this->dispatcher->dispatch($this->event);
        $this->assertEquals(1, $this->event->getCount());
    }

    public function testPropagationStopped(): void
    {
        $this->setUpProvider(
            [
                function (object $event) {
                    $event->increment();
                    $event->stopPropagation();
                }
            ],
            [
                function (object $event) {
                    $event->increment();
                }
            ]
        );

        $this->dispatcher->dispatch($this->event);
        $this->dispatcher->dispatch($this->event);

        $this->assertEquals(1, $this->event->getCount());
    }

    private function setUpProvider(array $listenerConfig): void
    {
        $this->provider
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($this->event)
            ->willReturn($listenerConfig);
    }
}
