<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Adapter;

use Omar\DependencyInjection\Configuration\Configuration;
use Psr\Container\ContainerInterface;

final class AurynContainerFactory
{
    public function create(Configuration $config): ContainerInterface
    {
        $builder = new AurynConfigurationAssembler();
        $config->apply($builder);

        return new AurynContainer($builder->auryn());
    }
}
