<?php

declare(strict_types=1);

namespace SSEventDispatcher\UnitTest;

use PHPUnit\Framework\TestCase;
use SSEventDispatcher\EventListener;
use SSEventDispatcher\EventListenerable;
use SSEventDispatcher\InvalidListener;
use Throwable;

class EventListenerTest extends TestCase
{
    private EventListenerable $listener;

    protected function setUp(): void
    {
        $this->listener = new EventListener();    
    }

    public function testSetGetName(): void
    {
        $this->listener->setName('TestEvent');

        $this->assertSame('TestEvent', $this->listener->getName());
    }

    public function testSetGetPriority(): void
    {
        $this->listener->setPriority(4);

        $this->assertSame(4, $this->listener->getPriority());
    }

    public function testSetGetCallable(): void
    {
        $this->listener->setCallable(fn() => 'test');

        $this->assertIsCallable($this->listener->getCallable());
    }

    public function testValidate(): void
    {
        $this->listener->setName('TestEvent 1');
        $this->listener->setCallable(fn() => 'validation test');

        $this->listener->validate();
    
        $listener = new EventListener();
        $this->expectException(InvalidListener::class);
        $this->expectExceptionMessage('Please provide a valid listener. The listener must have set: name and callable');
  
        $listener->validate();
    }

    public function testInvalidListenerToString(): void
    {
        try {
            $this->listener->validate();
        } catch (Throwable $error) {
            $this->assertSame(
                "SSEventDispatcher\InvalidListener: [0]: Please provide a valid listener." .
                " The listener must have set: name and callable\n",
                "$error"
            );
        }
    }
}
