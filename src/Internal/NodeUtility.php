<?php

declare(strict_types=1);

namespace Manychois\Pompom\Internal;

use Closure;
use Dom\Comment;
use Dom\DocumentFragment;
use Dom\DocumentType;
use Dom\Element;
use Dom\HTMLDocument;
use Dom\Node;
use Generator;
use Manychois\Pompom\ContentResolverInterface as IContentResolver;
use RuntimeException;

/**
 * Utility for manipulating DOM nodes.
 */
class NodeUtility
{
    use HtmlElementCreateTrait;

    /**
     * @param HTMLDocument     $document The HTML document to create nodes in.
     * @param IContentResolver $contents Resolver to convert mixed children to Node.
     */
    public function __construct(
        private readonly HTMLDocument $document,
        private readonly IContentResolver $contents,
    ) {
    }

    /**
     * Changes the attributes of an element.
     *
     * @param Element             $element    The element to change the attributes of.
     * @param string|array<mixed> $attributes The attributes to change.
     *
     * @return Element The given element.
     */
    public function changeAttributes(Element $element, string|array $attributes): Element
    {
        if (is_string($attributes)) {
            $attributes = ['class' => $attributes];
        }
        foreach ($attributes as $name => $value) {
            if (is_int($name)) {
                $this->contents->changeAttributes($element, $value, '');
            } else {
                $this->contents->changeAttributes($element, $name, $value);
            }
        }
        return $element;
    }

    /**
     * Creates a comment node.
     *
     * @param string $data Comment text content.
     *
     * @return Comment The created comment node.
     */
    public function createComment(string $data): Comment
    {
        return $this->document->createComment($data);
    }

    /**
     * Creates a document type (DOCTYPE) node.
     *
     * @param string $qualifiedName DTD name (e.g. "html" for <!DOCTYPE html>).
     * @param string $publicId      Public identifier of the external subset.
     * @param string $systemId      System identifier of the external subset.
     *
     * @return DocumentType The created document type node.
     */
    public function createDoctype(
        string $qualifiedName = 'html',
        string $publicId = '',
        string $systemId = '',
    ): DocumentType {
        $doctype = $this->document->implementation->createDocumentType($qualifiedName, $publicId, $systemId);
        $doctype = $this->document->importNode($doctype);
        assert($doctype instanceof DocumentType);
        return $doctype;
    }

    /**
     * Creates an element with attributes and children.
     *
     * @param string              $tagName    Element tag name in lowercase.
     * @param string|array<mixed> $attributes Attributes of the element in key-value pairs.
     *                                        If a string is given, it is treated as the class attribute.
     *                                        If value is null, the attribute is removed.
     * @param mixed               $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function createElement(
        string $tagName,
        string|array $attributes = [],
        mixed $children = null,
    ): Element {
        $element = $this->document->createElement($tagName);
        $this->changeAttributes($element, $attributes);
        foreach ($this->contents->toNodes($this->document, $children) as $node) {
            $element->appendChild($node);
        }

        return $element;
    }

    /**
     * Loops through all ancestor elements of a given node.
     *
     * @param Node $node The node to loop through.
     *
     * @return Generator<int, Element, mixed, void> The ancestor elements of the given node.
     */
    public function loopAncestorElements(Node $node): Generator
    {
        $node = $node->parentNode;
        while ($node instanceof Element) {
            yield $node;
            $node = $node->parentNode;
        }
    }

    /**
     * Loops through all descendant elements of a given node.
     *
     * @param Node    $node   The node to loop through.
     * @param Closure $filter The filter to apply to the nodes.
     *
     * @return Generator<int, Element, mixed, void> The descendant elements of the given node.
     *
     * @phpstan-param ?Closure(Element): bool $filter
     */
    public function loopDescendantElements(Node $node, ?Closure $filter = null): Generator
    {
        $i = 0;
        foreach ($this->loopDescendantNodes($node) as $n) {
            if ($n instanceof Element) {
                if ($filter === null || $filter($n)) {
                    yield $i => $n;
                    $i++;
                }
            }
        }
    }

    /**
     * Loops through all descendant nodes of a given node.
     *
     * @param Node    $node   The node to loop through.
     * @param Closure $filter The filter to apply to the nodes.
     *
     * @return Generator<int, Node, mixed, void> The descendant nodes of the given node.
     *
     * @phpstan-param ?Closure(Node): bool $filter
     */
    public function loopDescendantNodes(Node $node, ?Closure $filter = null): Generator
    {
        $nodes = [...$node->childNodes];
        while (count($nodes) > 0) {
            $current = array_shift($nodes);
            if ($filter === null || $filter($current)) {
                yield $current;
            }
            if ($current->hasChildNodes()) {
                array_unshift($nodes, ...$current->childNodes);
            }
        }
    }

    /**
     * Parses HTML into an element.
     *
     * @param string $html    The HTML to parse.
     * @param string $context The context element to use.
     *
     * @return Element The parsed element.
     */
    public function parseElement(string $html, string $context = 'body'): Element
    {
        $fragment = $this->parseHtml($html, $context);
        $element = $fragment->firstElementChild;
        if ($element === null) {
            throw new RuntimeException('Failed to parse HTML into an element.');
        }
        $element->remove();
        return $element;
    }

    /**
     * Parses HTML into a document fragment.
     *
     * @param string $html    The HTML to parse.
     * @param string $context The context element to use.
     *
     * @return DocumentFragment The parsed document fragment.
     */
    public function parseHtml(string $html, string $context = 'body'): DocumentFragment
    {
        $topElement = $this->document->createElement($context);
        $topElement->innerHTML = $html;
        $fragment = $this->document->createDocumentFragment();
        $fragment->append(...$topElement->childNodes);
        return $fragment;
    }
}
