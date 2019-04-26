<?php declare(strict_types=1);

namespace Omar\DependencyInjection\Exception;

use LogicException;
use Psr\Container\NotFoundExceptionInterface;
use function sprintf;

final class NotFound extends LogicException implements NotFoundExceptionInterface
{
    public function __construct(string $class)
    {
        parent::__construct(sprintf('Missing class or interface: %s', $class));
    }
}
