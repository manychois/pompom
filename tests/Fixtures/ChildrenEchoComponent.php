<?php

declare(strict_types=1);

namespace Manychois\PompomTests\Fixtures;

use Generator;
use Manychois\Pompom\AbstractComponent;

final class ChildrenEchoComponent extends AbstractComponent
{
    /**
     * @return Generator<int, mixed, mixed, void>
     */
    protected function getContent(): Generator
    {
        yield $this->nodeUtility->createElement('span', [], $this->children());
    }
}
