<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection\Setup;

use Lencse\Omar\DependencyInjection\Configuration\ConfigParamSetupConfiguration;
use Lencse\Omar\DependencyInjection\Configuration\ParamConfigurator;
use Lencse\Omar\DependencyInjection\Configuration\ParamSetup;

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
