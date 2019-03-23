<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Configuration;

interface SetupConfiguration
{
    public function class(): string;

    public function applyOnParamConfigurator(ParamConfigurator $configurator): void;
}
