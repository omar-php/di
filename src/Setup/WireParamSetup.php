<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Setup;

use Omar\DependencyInjection\Configuration\ParamConfigurator;
use Omar\DependencyInjection\Configuration\ParamSetup;
use Omar\DependencyInjection\Configuration\WireParamSetupConfiguration;

final class WireParamSetup implements ParamSetup, WireParamSetupConfiguration
{
    /** @var string */
    private $name;

    /** @var string */
    private $class;

    public function __construct(string $name, string $class)
    {
        $this->name = $name;
        $this->class = $class;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function class(): string
    {
        return $this->class;
    }

    public function apply(ParamConfigurator $configurator): void
    {
        $configurator->wire($this);
    }
}
