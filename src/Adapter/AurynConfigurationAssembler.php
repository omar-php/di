<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection\Adapter;

use Auryn\Injector;
use Lencse\Omar\DependencyInjection\Configuration\BindConfiguration;
use Lencse\Omar\DependencyInjection\Configuration\ConfigurationAssembler;
use Lencse\Omar\DependencyInjection\Configuration\FactoryConfiguration;
use Lencse\Omar\DependencyInjection\Configuration\ProviderConfiguration;
use Lencse\Omar\DependencyInjection\Configuration\SetupConfiguration;

final class AurynConfigurationAssembler implements ConfigurationAssembler
{
    /** @var Injector */
    private $auryn;

    public function __construct()
    {
        $this->auryn = new Injector();
    }

    public function bind(BindConfiguration $bind): void
    {
        $this->auryn->alias($bind->abstract(), $bind->concrete());
    }

    public function setup(SetupConfiguration $setup): void
    {
        $configurator = new AurynParamConfigurator();
        $setup->applyOnParamConfigurator($configurator);
        $configurator->applyToAurynInjector($this->auryn, $setup->class());
    }

    public function provider(ProviderConfiguration $provider): void
    {
        $this->auryn->delegate($provider->class(), $provider->closure());
    }

    public function factory(FactoryConfiguration $factory): void
    {
        $this->auryn->delegate($factory->class(), $factory->factoryClass());
    }

    public function auryn(): Injector
    {
        return $this->auryn;
    }
}
