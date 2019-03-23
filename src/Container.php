<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection;

use Lencse\Omar\DependencyInjection\Adapter\AurynContainerFactory;
use Lencse\Omar\DependencyInjection\Configuration\ConfigBuilder;
use Psr\Container\ContainerInterface;

final class Container
{
    public static function create(?ConfigBuilder $configuration = null): ContainerInterface
    {
        $factory = new AurynContainerFactory();

        return $factory->create($configuration ?: Config::init());
    }
}
