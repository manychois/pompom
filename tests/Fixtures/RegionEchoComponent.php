<?php

declare(strict_types=1);

namespace Manychois\PompomTests\Fixtures;

use Generator;
use Manychois\Pompom\AbstractComponent;

final class RegionEchoComponent extends AbstractComponent
{
    /**
     * @return Generator<int, mixed, mixed, void>
     */
    protected function getContent(): Generator
    {
        yield $this->nodeUtility->createElement('span', [], $this->region('slot'));
    }
}
