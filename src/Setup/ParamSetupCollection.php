<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Setup;

use Omar\DependencyInjection\Configuration\ParamConfigurator;
use Omar\DependencyInjection\Configuration\ParamSetup;
use Omar\DependencyInjection\Configuration\ParamSetupConfiguration;

final class ParamSetupCollection implements ParamSetupConfiguration
{
    /** @var ParamSetup[] */
    private $paramSetups = [];

    private function add(ParamSetup $paramSetup): ParamSetupConfiguration
    {
        $result = new self();
        $result->paramSetups = $this->paramSetups;
        $result->paramSetups[] = $paramSetup;

        return $result;
    }

    public function wire(string $name, string $class): ParamSetupConfiguration
    {
        return $this->add(new WireParamSetup($name, $class));
    }

    /**
     * @param mixed $value
     */
    public function config(string $name, $value): ParamSetupConfiguration
    {
        return $this->add(new ConfigParamSetup($name, $value));
    }

    public function apply(ParamConfigurator $configurator): void
    {
        foreach ($this->paramSetups as $paramSetup) {
            $paramSetup->apply($configurator);
        }
    }
}
