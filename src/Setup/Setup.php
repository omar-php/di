<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Setup;

use Omar\DependencyInjection\Configuration\ConfigurationAssembler;
use Omar\DependencyInjection\Configuration\ParamConfigurator;
use Omar\DependencyInjection\Configuration\ParamSetupConfiguration;
use Omar\DependencyInjection\Configuration\Setting;
use Omar\DependencyInjection\Configuration\SetupConfiguration;

final class Setup implements Setting, SetupConfiguration
{
    /** @var string */
    private $class;

    /** @var ParamSetupConfiguration */
    private $params;

    public function __construct(string $class, ParamSetupConfiguration $params)
    {
        $this->class = $class;
        $this->params = $params;
    }

    public function class(): string
    {
        return $this->class;
    }

    public function applyOnParamConfigurator(ParamConfigurator $configurator): void
    {
        $this->params->apply($configurator);
    }

    public function apply(ConfigurationAssembler $builder): void
    {
        $builder->setup($this);
    }
}
