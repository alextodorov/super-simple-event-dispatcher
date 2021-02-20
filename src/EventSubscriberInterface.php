<?php

namespace SSEventDispatcher;

interface EventSubscriberInterface
{
    public function getSubscribeEvents(): iterable;
}
