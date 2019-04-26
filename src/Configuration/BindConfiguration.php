<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Configuration;

interface BindConfiguration
{
    public function abstract(): string;

    public function concrete(): string;
}
