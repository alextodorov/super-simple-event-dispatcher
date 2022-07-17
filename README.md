# Super Simple Event Dispatcher

A super simple event dispatcher library implementing PSR-14

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

$provider->addListener($listener, $eventName, $priority);

// New dispatcher
$dispatcher = new EventDispatcher($provider);

// Dispatch the event
$dispatcher->dispatch($event);
```

For more details check out the [wiki].

[wiki]: https://github.com/alextodorov/super-simple-event-dispatcher/wiki/Basic-Usage