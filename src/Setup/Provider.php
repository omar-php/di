<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection\Setup;

use Closure;
use Lencse\Omar\DependencyInjection\Configuration\ConfigurationAssembler;
use Lencse\Omar\DependencyInjection\Configuration\ProviderConfiguration;
use Lencse\Omar\DependencyInjection\Configuration\Setting;

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
