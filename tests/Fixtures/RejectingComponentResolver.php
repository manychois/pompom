<?php

declare(strict_types=1);

namespace Manychois\PompomTests\Fixtures;

use InvalidArgumentException;
use Manychois\Pompom\ComponentResolverInterface;

/**
 * Resolver that never resolves a component (for tests that only need an Engine with ContentResolver).
 */
final class RejectingComponentResolver implements ComponentResolverInterface
{
    public function has(string $name): bool
    {
        return false;
    }

    public function resolve(string $name): string
    {
        throw new InvalidArgumentException('No component in this test engine.');
    }
}
