<?php

declare(strict_types=1);

namespace Manychois\PompomTests\Fixtures;

use InvalidArgumentException;
use Manychois\Pompom\AbstractComponent;
use Manychois\Pompom\ComponentResolverInterface;

/**
 * @phpstan-type ComponentMap array<string, class-string<AbstractComponent>>
 */
final class MapComponentResolver implements ComponentResolverInterface
{
    /**
     * @param ComponentMap $map
     */
    public function __construct(private array $map)
    {
    }

    public function has(string $name): bool
    {
        return isset($this->map[$name]);
    }

    public function resolve(string $name): string
    {
        if (!isset($this->map[$name])) {
            throw new InvalidArgumentException(sprintf('Unknown component "%s".', $name));
        }

        return $this->map[$name];
    }
}
