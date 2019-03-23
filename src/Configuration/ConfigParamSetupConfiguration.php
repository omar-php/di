<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Configuration;

interface ConfigParamSetupConfiguration
{
    public function name(): string;

    /**
     * @return mixed
     */
    public function value();
}
