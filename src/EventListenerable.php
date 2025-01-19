<?php

declare(strict_types=1);

namespace SSEventDispatcher;

interface EventListenerable
{
    public function getName(): string;
    public function setName(string $name): void;
    public function setPriority(int $priority = 1): void;
    public function getPriority(): int;
    public function setCallable(callable $callable): void;
    public function getCallable(): callable;
    public function validate(): void;
}
