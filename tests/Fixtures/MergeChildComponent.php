<?php

declare(strict_types=1);

namespace Manychois\PompomTests\Fixtures;

use Generator;
use Manychois\Pompom\AbstractComponent;

final class MergeChildComponent extends AbstractComponent
{
    /**
     * @return Generator<int, mixed, mixed, void>
     */
    protected function getContent(): Generator
    {
        yield json_encode($this->props, JSON_THROW_ON_ERROR);
    }
}
