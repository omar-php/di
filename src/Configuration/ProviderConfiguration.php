<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Configuration;

use Closure;

interface ProviderConfiguration
{
    public function class(): string;

    public function closure(): Closure;
}
