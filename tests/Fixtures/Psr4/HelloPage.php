<?php

declare(strict_types=1);

namespace Manychois\PompomTests\Fixtures\Psr4;

use Generator;
use Manychois\Pompom\AbstractComponent;

/**
 * Fixture component resolved from the name "hello-page".
 */
final class HelloPage extends AbstractComponent
{
    /**
     * @return Generator<int, mixed, mixed, void>
     */
    protected function getContent(): Generator
    {
        yield $this->nodeUtility->createElement('span', [], 'hello-psr4');
    }
}
