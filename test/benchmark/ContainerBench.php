<?php declare(strict_types=1);

namespace Test\Benchmark;

use Fixtures\A;
use Fixtures\I1;
use Fixtures\I2;
use Omar\DependencyInjection\Configuration\ConfigBuilder;
use Omar\DependencyInjection\Container;
use Omar\DependencyInjection\Exception\ContainerSetupError;
use Omar\DependencyInjection\Exception\NotFound;
use Psr\Container\ContainerInterface;
use function assert;

class ContainerBench
{
    public function __construct()
    {
        require_once __DIR__ . '/fixtures/classes.php';
    }

    public function benchContainerWithoutSetup(): void
    {
        $this->container();
    }

    public function bencHasForClass(): void
    {
        $this->container()->has(A::class);
        assert($this->container()->has(I1::class));
    }

    public function benchHasForInvalidClassName(): void
    {
        assert(! $this->container()->has('Invalid'));
    }

    public function benchHasForNumber(): void
    {
        assert(! $this->container()->has(0));
    }

    public function benchMake1ClassWithoutSetup(): void
    {
        assert($this->container()->get(A::class) instanceof A);
    }

    public function benchNotFoundException(): void
    {
        try {
            $this->container()->get('Invalid');
        } catch (NotFound $e) {
            return;
        }
    }

    public function benchContainerSetupException(): void
    {
        try {
            $this->container()->get(I2::class);
        } catch (ContainerSetupError $e) {
            return;
        }
    }

    private function container(?ConfigBuilder $config = null): ContainerInterface
    {
        return Container::create($config);
    }
}
