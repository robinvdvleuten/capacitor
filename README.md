# Capacitor

Flux(ible) data flow for PHP apps.

<img src="https://media.giphy.com/media/C6Vy7zS5ERdHG/giphy.gif" title="Flux Capacitor" alt="Flux Capacitor" width="200">

[![Build Status](https://travis-ci.org/robinvdvleuten/capacitor.svg?branch=master)](https://travis-ci.org/robinvdvleuten/capacitor)

## What is it?

You've probably never heard of the ideas behind [Flux](https://facebook.github.io/react/) in PHP userland. It's an unidirectional data flow pattern used by Javascript applications to implement an user interface that responds to user actions. To us it looks like an enhanced message bus with event sourcing capabilities and is definitely something we can use in our PHP applications.

## Installation

The recommended way to install the library is through [Composer](https://getcomposer.org/);

```bash
composer require rvdv/capacitor
```

## Usage - at a glance

The most simple implementation of Capacitor is the message bus pattern where multiple listeners can subscribe to dispatched messages;

```php
use Capacitor\Capacitor;

$bus = new Capacitor();

$unsubscribe = $bus->subscribe(function ($message) {
    var_dump($message); // Outputs "message"
    return $message;
});

$bus->subscribe(function ($message) {
    var_dump($message); // Outputs "message"; "another message"
    return $message;
});

$bus('message');

$unsubscribe();

$bus('another message');
```

For this and other implementations, take a look at the `/examples` directory.

## Tests

To run the test suite, you need install the dependencies via composer, then run PHPUnit.

```bash
composer install
php vendor/bin/phpunit
```

## License

MIT © [Robin van der Vleuten](https://www.robinvdvleuten.nl)
