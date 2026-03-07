<?php

declare(strict_types=1);

namespace Manychois\Pompom;

use Dom\HTMLDocument;
use Dom\Node;
use Generator;

interface NodableInterface
{
    /**
     * Converts the object to a generator of nodes.
     *
     * @param Engine       $engine   The engine to use to convert the object to nodes.
     * @param HTMLDocument $document The document to use to convert the object to nodes.
     * @return Generator<int, Node, mixed, void>
     */
    public function toNodes(Engine $engine, HTMLDocument $document): Generator;
}
