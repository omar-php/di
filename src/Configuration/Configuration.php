<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Configuration;

interface Configuration
{
    public function apply(ConfigurationAssembler $assembler): void;
}
