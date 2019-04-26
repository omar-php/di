<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Adapter;

use Auryn\InjectionException;
use Auryn\Injector;
use Omar\DependencyInjection\Exception\ContainerSetupError;
use Omar\DependencyInjection\Exception\NotFound;
use Psr\Container\ContainerInterface;
use function class_exists;
use function interface_exists;

final class AurynContainer implements ContainerInterface
{
    /** @var Injector */
    private $auryn;

    /** @var mixed[] */
    private $instances = [];

    public function __construct(Injector $auryn)
    {
        $this->auryn = $auryn;
    }

    public function get($id): object
    {
        $class = $this->castToString($id);
        $this->verifyClass($class);
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }
        $result = $this->makeOrFail($class);
        $this->instances[$class] = $result;
        $this->auryn->share($result);

        return $result;
    }

    public function has($id): bool
    {
        $class = $this->castToString($id);

        return class_exists($class) || interface_exists($class);
    }

    private function makeOrFail(string $class): object
    {
        try {
            return $this->auryn->make($class);
        } catch (InjectionException $e) {
            throw new ContainerSetupError($class, $e);
        }
    }

    private function verifyClass(string $class): void
    {
        if (! $this->has($class)) {
            throw new NotFound($class);
        }
    }

    /**
     * @param string $id
     *
     * @return string
     */
    private function castToString($id): string
    {
        return (string) $id;
    }
}
