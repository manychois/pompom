<?php

declare(strict_types=1);

namespace Manychois\Pompom;

use Dom\Element;
use Dom\HTMLDocument;
use Dom\Node;
use Generator;
use TypeError;

/**
 * Converts mixed data to Dom\Node (zero or more nodes).
 *
 * Implementations use the given document to create or import nodes.
 */
interface ContentResolverInterface
{
    /**
     * Changes the attributes of an element.
     *
     * @param Element $element The element to change the attributes of.
     * @param mixed   $name    The name of the attribute to change.
     * @param mixed   $value   The value of the attribute to change.
     */
    public function changeAttributes(Element $element, mixed $name, mixed $value): void;

    /**
     * Changes the classlist of an element.
     *
     * @param Element                   $element   The element to change the classlist of.
     * @param string|array<bool|string> $classlist The classlist to change.
     */
    public function changeClasslist(Element $element, string|array $classlist): void;

    /**
     * Sets the class attribute of an element.
     *
     * @param Element                   $element   The element to set the classname of.
     * @param string|array<bool|string> $classname The classname to set.
     */
    public function setClassName(Element $element, string|array $classname): void;

    /**
     * Converts mixed content to zero or more Dom\Node for the given document.
     *
     * @param HTMLDocument $document Document used to create or import nodes.
     * @param mixed        $content  Data to convert (e.g. scalar, null, Node, or iterable).
     *
     * @return Generator<int, Node, mixed, void>
     *
     * @throws TypeError When content cannot be converted to nodes.
     */
    public function toNodes(HTMLDocument $document, mixed $content): Generator;
}
