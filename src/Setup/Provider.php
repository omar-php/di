<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Setup;

use Closure;
use Omar\DependencyInjection\Configuration\ConfigurationAssembler;
use Omar\DependencyInjection\Configuration\ProviderConfiguration;
use Omar\DependencyInjection\Configuration\Setting;

final class Provider implements Setting, ProviderConfiguration
{
    /** @var string */
    private $class;

    /** @var Closure */
    private $closure;

    public function __construct(string $class, Closure $closure)
    {
        $this->class = $class;
        $this->closure = $closure;
    }

    public function apply(ConfigurationAssembler $builder): void
    {
        $builder->provider($this);
    }

    public function class(): string
    {
        return $this->class;
    }

    public function closure(): Closure
    {
        return $this->closure;
    }
}
