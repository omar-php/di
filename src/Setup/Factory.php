<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Setup;

use Omar\DependencyInjection\Configuration\ConfigurationAssembler;
use Omar\DependencyInjection\Configuration\FactoryConfiguration;
use Omar\DependencyInjection\Configuration\Setting;

final class Factory implements Setting, FactoryConfiguration
{
    /** @var string */
    private $class;

    /** @var string */
    private $factoryClass;

    public function __construct(string $class, string $factoryClass)
    {
        $this->class = $class;
        $this->factoryClass = $factoryClass;
    }

    public function apply(ConfigurationAssembler $builder): void
    {
        $builder->factory($this);
    }

    public function class(): string
    {
        return $this->class;
    }

    public function factoryClass(): string
    {
        return $this->factoryClass;
    }
}
