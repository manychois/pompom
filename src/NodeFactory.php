<?php

declare(strict_types=1);

namespace Manychois\Pompom;

use Dom\DocumentType;
use Dom\Element;
use Dom\HTMLDocument;
use Dom\Node;
use Dom\Text;

/**
 * Optional utility for creating DOM elements and text nodes from an HTML document.
 *
 * Accepts HTMLDocument and ContentResolverInterface in the constructor; use createElement(),
 * createText(), and createDoctype() to build nodes. Children in createElement() are
 * resolved via the content resolver (mixed to Node).
 */
final class NodeFactory
{
    /**
     * @param HTMLDocument             $document The HTML document to create nodes in.
     * @param ContentResolverInterface $contents Resolver to convert mixed children to Node.
     */
    public function __construct(
        private readonly HTMLDocument $document,
        private readonly ContentResolverInterface $contents,
    ) {}

    /**
     * Creates a document type (DOCTYPE) node.
     *
     * The returned node is not attached to the document; insert it (e.g. before the
     * document element) with document->insertBefore($doctype, document->documentElement).
     *
     * @param string $qualifiedName DTD name (e.g. "html" for <!DOCTYPE html>).
     * @param string $publicId      Public identifier of the external subset.
     * @param string $systemId      System identifier of the external subset.
     * @return DocumentType
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
     * Creates an element with optional attributes and children.
     *
     * Children are converted to nodes via the content resolver (mixed to Node).
     *
     * @param string                                           $tagName    Element tag name.
     * @param array<string, string|integer|float|boolean|null> $attributes Attr name => value.
     * @param mixed                                            $children   Optional children; null = none.
     * @return Element
     */
    public function createElement(
        string $tagName,
        array $attributes = [],
        mixed $children = null,
    ): Element {
        $element = $this->document->createElement($tagName);

        foreach ($attributes as $name => $value) {
            if ($value === null) {
                continue;
            }

            $element->setAttribute($name, (string) $value);
        }

        foreach ($this->contents->toNodes($this->document, $children) as $node) {
            $element->appendChild($node);
        }

        return $element;
    }

    /**
     * Creates a text node from a string.
     *
     * @param string $content Text content.
     * @return Text
     */
    public function createText(string $content): Text
    {
        return $this->document->createTextNode($content);
    }
}
