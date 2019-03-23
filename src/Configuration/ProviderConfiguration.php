<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection\Configuration;

use Closure;

interface ProviderConfiguration
{
    public function class(): string;

    public function closure(): Closure;
}
