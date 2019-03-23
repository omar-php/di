<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection\Configuration;

interface FactoryConfiguration
{
    public function class(): string;

    public function factoryClass(): string;
}
