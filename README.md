# Omar Dependency Injector

[![Packagist](https://img.shields.io/packagist/v/lencse/omar-di.svg)](https://packagist.org/packages/lencse/omar-di)
[![Build Status](https://travis-ci.org/lencse/omar-di.svg?branch=master)](https://travis-ci.org/lencse/omar-di)
[![Coverage Status](https://coveralls.io/repos/github/lencse/omar-di/badge.svg?branch=master)](https://coveralls.io/github/lencse/omar-di?branch=master)
[![StyleCI](https://github.styleci.io/repos/176080296/shield?branch=master)](https://github.styleci.io/repos/176080296)
[![Infection MSI](https://badge.stryker-mutator.io/github.com/lencse/omar-di/master)](https://infection.github.io)

[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=omar-di&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=omar-di)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=omar-di&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=omar-di)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=omar-di&metric=alert_status)](https://sonarcloud.io/dashboard?id=omar-di)

A [PSR-11](https://www.php-fig.org/psr/psr-11/) compliant Dependency Injector component

## Installation

With [composer](https://getcomposer.org/):

```bash
composer require lencse/omar-di
```

## Usage

### Building a simple class

You can instantiate a class without a constructor parameter with zero configuration.
 
```php
use Lencse\Omar\DependencyInjection\Container;

class RockBand {}

$container = Container::create();

$band = $container->get(RockBand::class);
assert($band instanceof RockBand);
```

### Autowiring

OmarDI can autowire parameter constructors.

```php
use Lencse\Omar\DependencyInjection\Container;

class Guitarist {}

class Drummer {}

class RockBand
{
    public function __construct(Guitarist $guitarist, Drummer $drummer) {}
}

$container = Container::create();

$band = $container->get(RockBand::class);
assert($band instanceof RockBand);
```

### Binding

You can bind implementations to abstract classes and interfaces with configuration.

```php
use Lencse\Omar\DependencyInjection\Config;
use Lencse\Omar\DependencyInjection\Container;

interface Guitarist {}
abstract class Drummer {}

class JimmyPage implements Guitarist {}
class JohnBonham extends Drummer {}

$config = Config::init()
    ->bind(Guitarist::class, JimmyPage::class)
    ->bind(Drummer::class, JohnBonham::class);

$container = Container::create($config);

$guitarist = $container->get(Guitarist::class);
$drummer = $container->get(Drummer::class);

assert($guitarist instanceof JimmyPage);
assert($drummer instanceof JohnBonham);
```

Autowiring can use the bound parameters.

```php
use Lencse\Omar\DependencyInjection\Config;
use Lencse\Omar\DependencyInjection\Container;

interface Guitarist {}
abstract class Drummer {}

class JimmyPage implements Guitarist {}
class JohnBonham extends Drummer {}

class RockBand
{
    public function __construct(Guitarist $guitarist, Drummer $drummer) {}
}

$config = Config::init()
    ->bind(Guitarist::class, JimmyPage::class)
    ->bind(Drummer::class, JohnBonham::class);

$container = Container::create($config);

$band = $container->get(RockBand::class);
assert($band instanceof RockBand);
```

### Setup constructor parameters

#### Wire implementations to constructor parameter

You can wire the constructor parameters by their name. That's how you can inject different
implementations for the same interfaces.

```php
use Lencse\Omar\DependencyInjection\Config;
use Lencse\Omar\DependencyInjection\Container;
use Lencse\Omar\DependencyInjection\Setup;

interface Guitarist {}
interface Drummer {}

class JimmyPage implements Guitarist {}
class Slash implements Guitarist {}
class JohnBonham implements Drummer {}
class StevenAdler implements Drummer {}

abstract class RockBand
{
    public function __construct(Guitarist $guitarist, Drummer $drummer) {}
}

class LedZeppelin extends RockBand {}
class GunsNRoses extends RockBand {}

$config = Config::init()
    ->setup(LedZeppelin::class, Config::params()
        ->wire('guitarist', JimmyPage::class)
        ->wire('drummer', JohnBonham::class)
    )
    ->setup(GunsNRoses::class, Config::params()
        ->wire('guitarist', Slash::class)
        ->wire('drummer', StevenAdler::class)
    );

$container = Container::create($config);

$ledzep = $container->get(LedZeppelin::class);
assert($ledzep instanceof LedZeppelin);

$gnr = $container->get(GunsNRoses::class);
assert($gnr instanceof GunsNRoses);
```

#### Configure values to constructor parameters

Scalar values or object instances can be configured to constructor parameters by their name.

```php
use Lencse\Omar\DependencyInjection\Config;
use Lencse\Omar\DependencyInjection\Container;
use Lencse\Omar\DependencyInjection\Setup;

class Guitarist
{
    public function __construct(string $guitarType, int $bornInYear) {}
}

$config = Config::init()
    ->setup(Guitarist::class, Config::params()
        ->config('guitarType', 'Les Paul')
        ->config('bornInYear', 1944)
    );

$container = Container::create($config);

$jimmyPage = $container->get(Guitarist::class);
assert($jimmyPage instanceof Guitarist);
```

#### Configured and wired parameters

They can be used together.

```php
use Lencse\Omar\DependencyInjection\Config;
use Lencse\Omar\DependencyInjection\Container;
use Lencse\Omar\DependencyInjection\Setup;

interface Guitar {}
class LesPaul implements Guitar {}

class Guitarist
{
    public function __construct(Guitar $guitar, int $bornInYear) {}
}

$config = Config::init()
    ->setup(Guitarist::class, Config::params()
        ->wire('guitar', LesPaul::class)
        ->config('bornInYear', 1944)
    );

$container = Container::create($config);

$jimmyPage = $container->get(Guitarist::class);
assert($jimmyPage instanceof Guitarist);
```

### Provider callback functions

You can set up your instances to be created with a callback function. These functions can also
get their parameters from the container. 

```php
use Lencse\Omar\DependencyInjection\Config;
use Lencse\Omar\DependencyInjection\Container;

interface Guitar {}
class LesPaul implements Guitar {}

class Guitarist
{
    public function __construct(Guitar $guitar, int $bornInYear) {}
}

$config = Config::init()
    ->bind(Guitar::class, LesPaul::class)
    ->provider(Guitarist::class, function (Guitar $guitar) {
        return new Guitarist($guitar, 1944);
    });

$container = Container::create($config);

$jimmyPage = $container->get(Guitarist::class);
assert($jimmyPage instanceof Guitarist);
```

### Factory classes

You can create your instances with factories. These factories can be configured with the parameters
in the constructor and in the __invoke() method.

```php
use Lencse\Omar\DependencyInjection\Config;
use Lencse\Omar\DependencyInjection\Container;

interface Guitar {}
class LesPaul implements Guitar {}

class Guitarist
{
    public function __construct(Guitar $guitar) {}
}

interface Drummer {}
class JohnBonham implements Drummer {}

class RockBand
{
    public function __construct(Guitarist $guitarist, Drummer $drummer) {}
}

class RockBandFactory
{
    /** @var Drummer */
    private $drummer;

    public function __construct(Drummer $drummer) {
        $this->drummer = $drummer;
    }

    public function __invoke(Guitar $guitar): RockBand
    {
        return new RockBand(new Guitarist($guitar), $this->drummer);
    }


}

$config = Config::init()
    ->bind(Drummer::class, JohnBonham::class)
    ->bind(Guitar::class, LesPaul::class)
    ->factory(RockBand::class, RockBandFactory::class);

$container = Container::create($config);

$band = $container->get(RockBand::class);
assert($band instanceof RockBand);
```

## Contributing

* Pull requests are welcome.
    * For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)
