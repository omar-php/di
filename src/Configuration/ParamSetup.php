<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Configuration;

interface ParamSetup
{
    public function apply(ParamConfigurator $configurator): void;
}
