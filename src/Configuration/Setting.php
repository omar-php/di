<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection\Configuration;

interface Setting
{
    public function apply(ConfigurationAssembler $builder): void;
}
