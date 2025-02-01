<?php

declare(strict_types=1);

namespace SSEventDispatcher\UnitTest;

use Psr\EventDispatcher\StoppableEventInterface;

class EventTester implements StoppableEventInterface
{
    private bool $stoppable = false;

    public function isPropagationStopped(): bool
    {
        return $this->stoppable;
    }

    public function stopPropagation(): void
    {
        $this->stoppable = true;
    }

    private int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
