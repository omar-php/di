<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Setup;

use Omar\DependencyInjection\Configuration\ConfigParamSetupConfiguration;
use Omar\DependencyInjection\Configuration\ParamConfigurator;
use Omar\DependencyInjection\Configuration\ParamSetup;

final class ConfigParamSetup implements ParamSetup, ConfigParamSetupConfiguration
{
    /** @var string */
    private $name;

    /** @var mixed */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    public function apply(ParamConfigurator $configurator): void
    {
        $configurator->config($this);
    }
}
