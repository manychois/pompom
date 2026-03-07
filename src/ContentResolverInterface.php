<?php

declare(strict_types=1);

namespace Manychois\Pompom;

use Dom\HTMLDocument;
use Dom\Node;
use Generator;

/**
 * Converts mixed data to Dom\Node (zero or more nodes).
 *
 * Implementations use the given document to create or import nodes.
 */
interface ContentResolverInterface
{
    /**
     * Converts mixed content to zero or more Dom\Node for the given document.
     *
     * @param HTMLDocument $document Document used to create or import nodes.
     * @param mixed        $content  Data to convert (e.g. scalar, null, Node, or iterable).
     * @return Generator<int, Node, mixed, void>
     * @throws \TypeError When content cannot be converted to nodes.
     */
    public function toNodes(HTMLDocument $document, mixed $content): Generator;
}
