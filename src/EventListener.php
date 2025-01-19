<?php

declare(strict_types=1);

namespace SSEventDispatcher;

class EventListener implements EventListenerable
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

    public function validate(): void
    {
        if (isset($this->name) && isset($this->callable)) {
            return;
        }

        throw new InvalidListener('Please provide a valid listener. The listener must have set: name and callable');
    }
}
