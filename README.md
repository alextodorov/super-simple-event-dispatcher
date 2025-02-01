# Super Simple Event Dispatcher

A super simple event dispatcher library implementing PSR-14

![Build Status](https://github.com/alextodorov/super-simple-event-dispatcher/actions/workflows/phpunit.yml/badge.svg?branch=main) [![codecov](https://codecov.io/gh/alextodorov/super-simple-event-dispatcher/branch/main/graph/badge.svg?token=RO512GTO4W)](https://codecov.io/gh/alextodorov/super-simple-event-dispatcher) 

Install
-------

```sh
composer require super-simple/event-dispatcher
```

Requires PHP 8.4 or newer.

Usage
-----

Basic usage:

```php
require '/path/to/vendor/autoload.php';

// New provider
$provider = new EventProvider();

// MyListener class must implement EventListenerable
$listener = new MyListener();

$provider->addListener($listener);

// New dispatcher
$dispatcher = new EventDispatcher($provider);

// Dispatch the event
// Event class must implement Psr/EventDispatcher/StoppableEventInterface
$event = new Event();

$dispatcher->dispatch($event);
```