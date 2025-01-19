# Super Simple Event Dispatcher

A super simple event dispatcher library implementing PSR-14

![Build Status](https://github.com/alextodorov/super-simple-event-dispatcher/actions/workflows/phpunit.yml/badge.svg?branch=main) [![Latest Stable Version](http://poser.pugx.org/super-simple/event-dispatcher/v)](https://packagist.org/packages/super-simple/event-dispatcher) [![PHP Version Require](http://poser.pugx.org/super-simple/event-dispatcher/require/php)](https://packagist.org/packages/super-simple/event-dispatcher) [![License](http://poser.pugx.org/super-simple/event-dispatcher/license)](https://packagist.org/packages/super-simple/event-dispatcher) [![Total Downloads](http://poser.pugx.org/super-simple/event-dispatcher/downloads)](https://packagist.org/packages/super-simple/event-dispatcher)

Install
-------

```sh
composer require super-simple/event-dispatcher
```

Requires PHP 8.1 or newer.

Usage
-----

Basic usage:

```php
require '/path/to/vendor/autoload.php';

// New provider
$provider = new EventProvider();

// Register listener
$listener = new EventListener();
$listener->setName(Event::class);
$listener->setCallable(fn($event) => $event);

$provider->addListener($listener);

// New dispatcher
$dispatcher = new EventDispatcher($provider);

// Dispatch the event
$dispatcher->dispatch($event);
```

For more details check out the [wiki].

[wiki]: https://github.com/alextodorov/super-simple-event-dispatcher/wiki/Basic-Usage