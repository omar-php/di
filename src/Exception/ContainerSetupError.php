<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Exception;

use LogicException;
use Psr\Container\ContainerExceptionInterface;
use Throwable;
use function sprintf;

final class ContainerSetupError extends LogicException implements ContainerExceptionInterface
{
    public const CODE = 0;

    public function __construct(string $class, ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Cannot make class or interface: %s', $class),
            self::CODE,
            $previous
        );
    }
}
