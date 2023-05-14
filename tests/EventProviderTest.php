<?php

declare(strict_types=1);

namespace SSEventDispatcher\UnitTest;

use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\StoppableEventInterface;
use SSEventDispatcher\EventProvider;
use SSEventDispatcher\EventSubscriberInterface;
use SSEventDispatcher\InvalidSubscriber;
use stdClass;
use Iterator;

class EventProviderTest extends TestCase
{
    public function testAddGetListener()
    {
        $provider = new EventProvider();

        $event = new stdClass();

        $listener = new class {
            public function __invoke(object $event)
            {
                $event->weight = 2;
            }   
        };

        $provider->addListener($listener, stdClass::class, 2);

        $this->assertCount(1, $provider->getListenersForEvent($event));
    }

    public function testAddGetListenerViaSubscriber()
    {
        $provider = new EventProvider();

        $event = new stdClass();

        $subscriber = new class implements EventSubscriberInterface {
            public function getSubscribeEvents(): iterable
            {
                return [
                    [
                        'name' => stdClass::class,
                        'listener' => function (object $event) {},
                        'priority' => 2,
                    ],
                    [
                        'name' => stdClass::class,
                        'listener' => function (object $event) {},
                        'priority' => 1,
                    ]
                ];
            }   
        };

        $provider->addSubsciber($subscriber);

        $this->assertCount(2, $provider->getListenersForEvent($event));
    }

    public function testInvalidSubscriber()
    {
        $provider = new EventProvider();

        $subscriber = new class implements EventSubscriberInterface {
            public function getSubscribeEvents(): iterable
            {
                return [
                    [],
                ];
            }   
        };
                
        try {
            $provider->addSubsciber($subscriber);
        } catch (\Throwable $e) {
            $this->assertEquals(
                "SSEventDispatcher\InvalidSubscriber: [0]: Please provide a valid subscriber. The Array with keys listener, name, priority\n",
                "$e"
            );
        }

        $this->expectException(InvalidSubscriber::class);
        $this->expectErrorMessage('Please provide a valid subscriber. The Array with keys listener, name, priority');
  
        $provider->addSubsciber($subscriber);
    }

    public function testListenerOrder()
    {
        $provider = new EventProvider();

        $event = new class implements StoppableEventInterface {
            private bool $stoppable = false;

            public function isPropagationStopped(): bool
            {
                return $this->stoppable;
            }
        };
        
        $second = new class {
            public function __invoke(object $event)
            {
                
            }      
        };

        $first = new class {
            public function __invoke(object $event)
            {
                
            }      
        };

        $provider->addListener(
            $second,
            $event::class,
            3
        );
        
        $provider->addListener(
            $first,
            $event::class,
            2
        );

        /** @var Iterator $gen */
        $gen = $provider->getListenersForEvent($event);

        $this->assertInstanceOf($first::class, $gen->current());

        $gen->next();

        $this->assertInstanceOf($second::class, $gen->current());
    }
}