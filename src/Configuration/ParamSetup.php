<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection\Configuration;

interface ParamSetup
{
    public function apply(ParamConfigurator $configurator): void;
}
