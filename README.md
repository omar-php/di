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
    ->target(Guitarist::class)->bind(JimmyPage::class)
    ->target(Drummer::class)->bind(JohnBonham::class);

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
    ->target(Guitarist::class)->bind(JimmyPage::class)
    ->target(Drummer::class)->bind(JohnBonham::class);

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
    /** @var Guitarist */
    public $guitarist;

    /** @var Drummer */
    public $drummer;

    public function __construct(Guitarist $guitarist, Drummer $drummer) {
        $this->guitarist = $guitarist;
        $this->drummer = $drummer;
    }
}

class LedZeppelin extends RockBand {}
class GunsNRoses extends RockBand {}

$config = Config::init()
    ->target(LedZeppelin::class)->setup(
        Setup::wire('guitarist', JimmyPage::class)
            ->wire('drummer', JohnBonham::class)
    )
    ->target(GunsNRoses::class)->setup(
        Setup::wire('guitarist', Slash::class)
            ->wire('drummer', StevenAdler::class)
    );

$container = Container::create($config);

/** @var LedZeppelin $ledzep */
$ledzep = $container->get(LedZeppelin::class);
assert($ledzep->guitarist instanceof JimmyPage);
assert($ledzep->drummer instanceof JohnBonham);

/** @var GunsNRoses $gnr */
$gnr = $container->get(GunsNRoses::class);
assert($gnr->guitarist instanceof Slash);
assert($gnr->drummer instanceof StevenAdler);
```

#### Configure values to constructor parameters

Scalar values or object instances can be configured to constructor parameters by their name.

```php
use Lencse\Omar\DependencyInjection\Config;
use Lencse\Omar\DependencyInjection\Container;
use Lencse\Omar\DependencyInjection\Setup;

class Guitarist
{
    /** @var string */
    public $guitarType;

    /** @var int */
    public $bornInYear;

    public function __construct(string $guitarType, int $bornInYear) {
        $this->guitarType = $guitarType;
        $this->bornInYear = $bornInYear;
    }
}

$config = Config::init()
    ->target(Guitarist::class)->setup(
        Setup::config('guitarType', 'Les Paul')
            ->config('bornInYear', 1944)
    );

$container = Container::create($config);

/** @var Guitarist $jimmy */
$jimmy = $container->get(Guitarist::class);
assert('Les Paul' === $jimmy->guitarType);
assert(1944 === $jimmy->bornInYear);
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
    /** @var Guitar */
    public $guitar;

    /** @var int */
    public $bornInYear;

    public function __construct(Guitar $guitar, int $bornInYear) {
        $this->guitar = $guitar;
        $this->bornInYear = $bornInYear;
    }
}

$config = Config::init()
    ->target(Guitarist::class)->setup(
        Setup::wire('guitar', LesPaul::class)
            ->config('bornInYear', 1944)
    );

$container = Container::create($config);

/** @var Guitarist $jimmy */
$jimmy = $container->get(Guitarist::class);
assert($jimmy->guitar instanceof LesPaul);
assert(1944 === $jimmy->bornInYear);
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
    /** @var Guitar */
    public $guitar;

    /** @var int */
    public $bornInYear;

    public function __construct(Guitar $guitar, int $bornInYear) {
        $this->guitar = $guitar;
        $this->bornInYear = $bornInYear;
    }
}

$config = Config::init()
    ->target(Guitar::class)->bind(LesPaul::class)
    ->target(Guitarist::class)->provider(function (Guitar $guitar) {
        return new Guitarist($guitar, 1944);
    });

$container = Container::create($config);

/** @var Guitarist $jimmy */
$jimmy = $container->get(Guitarist::class);
assert($jimmy->guitar instanceof LesPaul);
assert(1944 === $jimmy->bornInYear);
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
    /** @var Guitar */
    public $guitar;

    public function __construct(Guitar $guitar) {
        $this->guitar = $guitar;
    }
}

interface Drummer {}
class JohnBonham implements Drummer {}

class RockBand
{
    /** @var Guitarist */
    public $guitarist;

    /** @var Drummer */
    public $drummer;

    public function __construct(Guitarist $guitarist, Drummer $drummer) {
        $this->guitarist = $guitarist;
        $this->drummer = $drummer;
    }
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
    ->target(Drummer::class)->bind(JohnBonham::class)
    ->target(Guitar::class)->bind(LesPaul::class)
    ->target(RockBand::class)->factory(RockBandFactory::class);

$container = Container::create($config);

/** @var RockBand $band */
$band = $container->get(RockBand::class);
assert($band->guitarist->guitar instanceof LesPaul);
```

## Contributing

* Pull requests are welcome.
    * For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)
