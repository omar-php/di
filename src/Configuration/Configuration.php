<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection\Configuration;

interface Configuration
{
    public function apply(ConfigurationAssembler $assembler): void;
}
