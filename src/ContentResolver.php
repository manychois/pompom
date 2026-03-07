<?php

declare(strict_types=1);

namespace Manychois\Pompom;

use Closure;
use Dom\HTMLDocument;
use Dom\Node;
use Generator;

/**
 * Converts mixed data to Dom\Node using the document passed to toNodes().
 *
 * Use toNodes($document, $content) to turn strings, scalars, existing nodes,
 * or iterables into nodes suitable for appending to the document.
 */
final class ContentResolver implements ContentResolverInterface
{
    private readonly Engine $engine;

    /**
     * @param Engine $engine Engine used to resolve NodableInterface content.
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    #region implements ContentResolverInterface

    /**
     * Converts mixed content to zero or more Dom\Node.
     *
     * - string|int|float|bool: yields one text node.
     * - Node: yields the node (imported into the document if from another document).
     * - null: yields one empty text node.
     * - iterable: yields nodes for each item (recursively via toNodes).
     * - Other types: throws TypeError.
     *
     * @param HTMLDocument $document Document used to create or import nodes.
     * @param mixed        $content  Data to convert (scalar, null, Node, or iterable).
     * @return Generator<int, Node, mixed, void>
     * @throws \TypeError When content cannot be converted to nodes.
     */
    public function toNodes(HTMLDocument $document, mixed $content): Generator
    {
        if ($content === null) {
            // do nothing
        } elseif ($content instanceof Node) {
            yield $content;
        } elseif (is_scalar($content)) {
            yield $document->createTextNode((string) $content);
        } elseif (is_iterable($content)) {
            foreach ($content as $item) {
                yield from $this->toNodes($document, $item);
            }
        } elseif ($content instanceof NodableInterface) {
            yield from $content->toNodes($this->engine, $document);
        } elseif ($content instanceof Closure) {
            $result = $content->__invoke();
            yield from $this->toNodes($document, $result);
        } else {
            throw new \TypeError(sprintf('Cannot convert %s to nodes.', get_debug_type($content)));
        }
    }

    #endregion
}
