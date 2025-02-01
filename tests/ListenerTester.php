<?php

declare(strict_types=1);

namespace SSEventDispatcher\UnitTest;

use SSEventDispatcher\EventListenerable;

class ListenerTester implements EventListenerable
{
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    private int $priority = 1;

    public function setPriority(int $priority = 1): void 
    {
        $this->priority = $priority;
    }

    public function getPriority(): int 
    {
        return $this->priority;
    }

    private mixed $callable;

    public function setCallable(callable $callable): void
    {
        $this->callable = $callable;
    }

    public function getCallable(): callable
    {
        return $this->callable;
    }
}
