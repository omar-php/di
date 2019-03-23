<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection\Configuration;

interface ParamConfigurator
{
    public function wire(WireParamSetupConfiguration $wireParamSetup): void;

    public function config(ConfigParamSetupConfiguration $configParamSetup): void;
}
