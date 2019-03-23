<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection\Configuration;

interface ConfigurationAssembler
{
    public function bind(BindConfiguration $bind): void;

    public function setup(SetupConfiguration $setup): void;

    public function provider(ProviderConfiguration $provider): void;

    public function factory(FactoryConfiguration $factory): void;
}
