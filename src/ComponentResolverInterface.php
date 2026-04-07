<?php

declare(strict_types=1);

namespace Manychois\Pompom;

use InvalidArgumentException;

/**
 * Resolves a component identifier to its full class name.
 *
 * The name passed to the engine is an arbitrary string (e.g. "hello-page", "home").
 * Implementations map that identifier to a component class; the name does not have to
 * be or look like a class name.
 */
interface ComponentResolverInterface
{
    /**
     * Returns whether the given identifier can be resolved.
     *
     * @param string $name Component identifier to check.
     */
    public function has(string $name): bool;

    /**
     * Resolves an identifier to the full component class name.
     *
     * @param string $name Component identifier to resolve.
     *
     * @return class-string<AbstractComponent>
     *
     * @throws InvalidArgumentException When the name cannot be resolved.
     */
    public function resolve(string $name): string;
}
