<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Configuration;

interface ParamSetupConfiguration
{
    public function wire(string $name, string $class): self;

    /**
     * @param mixed $value
     */
    public function config(string $name, $value): self;

    public function apply(ParamConfigurator $configurator): void;
}
