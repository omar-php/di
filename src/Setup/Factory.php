<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection\Setup;

use Lencse\Omar\DependencyInjection\Configuration\ConfigurationAssembler;
use Lencse\Omar\DependencyInjection\Configuration\FactoryConfiguration;
use Lencse\Omar\DependencyInjection\Configuration\Setting;

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
