# Container Component

[![Latest Version](https://img.shields.io/badge/release-1.0.2-blue?style=for-the-badge)](https://www.presstify.com/pollen-solutions/container/)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)](LICENSE.md)
[![PHP Supported Versions](https://img.shields.io/badge/PHP->=7.4-8892BF?style=for-the-badge&logo=php)](https://www.php.net/supported-versions.php)

Pollen Solutions **Container** Component is a PSR-7 ready Dependencies Injection Container.

## Installation

```bash
composer require pollen-solutions/container
```

## Basic Usage

```php
use Pollen\Container\Container;

$container = new Container();

class Foo {
    public $bar;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }
}

class Bar {}

$container->add(Foo::class, function () use ($container){
    return new Foo($container->get(Bar::class));
});
$container->add(Bar::class);

$foo = $container->get(Foo::class);

var_dump($foo instanceof Foo);
var_dump($foo->bar instanceof Bar); 
```

## Service Providers

```php
use Pollen\Container\Container;
use Pollen\Container\ServiceProvider;

// Classes definitions
class Foo {
    public $bar;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }
}

class Bar {}

// Service Provider definition
class FooBarServiceProvider extends ServiceProvider
{
    protected $provides = [
        Foo::class,
        Bar::class
    ];

    public function register(): void
    {
        $this->getContainer()->add(Foo::class, function () {
            return new Foo($this->getContainer()->get(Bar::class));
        });

        $this->getContainer()->add(Bar::class);
    }
}

// Service Provider declaration
$container = new Container();

$container->addServiceProvider(new FooBarServiceProvider());

$foo = $container->get(Foo::class);

// Test
var_dump($foo instanceof Foo);
var_dump($foo->bar instanceof Bar);
```

### Bootable Service Providers
```php
// Classes definitions
class Foo {
    public $bar;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }

    public function onBoot(): void
    {
        var_dump(sprintf('%s booted through Service Provider!', __CLASS__));
    }
}

class Bar {}

// Service Provider definition
class FooBarServiceProvider extends BootableServiceProvider
{
    protected $provides = [
        Foo::class,
        Bar::class
    ];

    public function boot(): void
    {
        /** @var Foo::class $foo */
        $foo = $this->getContainer()->get(Foo::class);
        $foo->onBoot();
    }

    public function register(): void
    {
        $this->getContainer()->add(Foo::class, function () {
            return new Foo($this->getContainer()->get(Bar::class));
        });

        $this->getContainer()->add(Bar::class);
    }
}

// Service Provider declaration
$container = new Container();
$serviceProviders = [];

$container->addServiceProvider($serviceProviders[] = new FooBarServiceProvider());

// Boot all Bootable service providers
foreach ($serviceProviders as $serviceProvider) {
    if ($serviceProvider instanceof BootableServiceProviderInterface) {
        $serviceProvider->boot();
    }
}
exit;
```


## Delegate Containers

```php
use Pollen\Container\Container;

$mainContainer = new Container();
$delegateContainer = new Container();

$mainContainer->delegate($delegateContainer);

class Foo {
    public $bar;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }
}

class Bar {}

$mainContainer->add(Foo::class, function () use ($mainContainer){
    return new Foo($mainContainer->get(Bar::class));
});

$delegateContainer->add(Bar::class);

$foo = $mainContainer->get(Foo::class);

var_dump($foo instanceof Foo);
var_dump($foo->bar instanceof Bar);
```

## Auto Wiring

```php
use Pollen\Container\Container;

interface FooInterface {}
class Foo implements FooInterface {
    public $bar;

    public function __construct(BarInterface $bar)
    {
        $this->bar = $bar;
    }
}

interface BarInterface {}
class Bar implements BarInterface {}

$container = new Container();
$container->enableAutoWiring();

$container->add(FooInterface::class, Foo::class);
$container->add(BarInterface::class, Bar::class);

$foo = $container->get(Foo::class);

var_dump($foo instanceof Foo);
var_dump($foo->bar instanceof Bar);
```
