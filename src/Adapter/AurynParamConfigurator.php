<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection\Adapter;

use Auryn\Injector;
use Lencse\Omar\DependencyInjection\Configuration\ConfigParamSetupConfiguration;
use Lencse\Omar\DependencyInjection\Configuration\ParamConfigurator;
use Lencse\Omar\DependencyInjection\Configuration\WireParamSetupConfiguration;

final class AurynParamConfigurator implements ParamConfigurator
{
    /** @var mixed[] */
    private $setupArr = [];

    public function wire(WireParamSetupConfiguration $wireParamSetup): void
    {
        $this->setupArr[$wireParamSetup->name()] = $wireParamSetup->class();
    }

    public function config(ConfigParamSetupConfiguration $configParamSetup): void
    {
        $this->setupArr[':' . $configParamSetup->name()] = $configParamSetup->value();
    }

    public function applyToAurynInjector(Injector $auryn, string $class): void
    {
        $auryn->define($class, $this->setupArr);
    }
}
