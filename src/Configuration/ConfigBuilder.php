<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Configuration;

use Closure;

interface ConfigBuilder extends Configuration
{
    public function bind(string $target, string $className): self;

    public function setup(string $target, ParamSetupConfiguration $paramSetup): self;

    public function provider(string $target, Closure $provider): self;

    public function factory(string $target, string $factoryClassName): self;
}
