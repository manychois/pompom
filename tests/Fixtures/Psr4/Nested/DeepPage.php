<?php

declare(strict_types=1);

namespace Manychois\PompomTests\Fixtures\Psr4\Nested;

use Generator;
use Manychois\Pompom\AbstractComponent;

/**
 * Fixture component resolved from the name "nested/deep-page".
 */
final class DeepPage extends AbstractComponent
{
    /**
     * @return Generator<int, mixed, mixed, void>
     */
    protected function getContent(): Generator
    {
        yield 'nested-deep';
    }
}
