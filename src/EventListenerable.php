<?php

declare(strict_types=1);

namespace SSEventDispatcher;

interface EventListenerable
{
    public function getName(): string;
    public function getPriority(): int;
    public function getCallable(): callable;
}
