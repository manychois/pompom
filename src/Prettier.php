<?php

declare(strict_types=1);

namespace Manychois\Pompom;

use Dom\Comment;
use Dom\Element;
use Dom\HTMLDocument;
use Dom\Node;
use Dom\Text;

/**
 * Inserts whitespace into an HTML document so that saveHTML() produces indented,
 * human-friendly output.
 *
 * Four indent insertion positions (each either insert 'o' or not 'x'):
 * 1. Before-start: before the element's opening tag (inserted by parent).
 * 2. After-start: after the opening tag, before first child.
 * 3. Before-end: after last child, before the closing tag.
 * 4. After-end: after the element's closing tag (inserted by parent).
 *
 * Element styling is given by a 4-character pattern (e.g. oxxo). Elements are
 * assigned to an array named by their pattern. When an element is not in any
 * array, it is treated as oooo (insert at all positions). $xxxx = no indent.
 *
 * Pattern positions: [0]=before-start, [1]=after-start, [2]=before-end, [3]=after-end.
 * o = insert newline+indent, x = do not insert.
 *
 * All 15 pattern arrays are defined (even if empty) so extending classes may assign elements to any pattern.
 */
class Prettier
{
    /**
     * List of elements that use these indentation styles:
     * - Before start tag: yes
     * - After start tag: yes
     * - Before end tag: yes
     * - After end tag: no
     *
     * @var list<string>
     */
    protected array $ooox = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: yes
     * - After start tag: yes
     * - Before end tag: no
     * - After end tag: yes
     *
     * @var list<string>
     */
    protected array $ooxo = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: yes
     * - After start tag: yes
     * - Before end tag: no
     * - After end tag: no
     *
     * @var list<string>
     */
    protected array $ooxx = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: yes
     * - After start tag: no
     * - Before end tag: yes
     * - After end tag: yes
     *
     * @var list<string>
     */
    protected array $oxoo = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: yes
     * - After start tag: no
     * - Before end tag: yes
     * - After end tag: no
     *
     * @var list<string>
     */
    protected array $oxox = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: yes
     * - After start tag: no
     * - Before end tag: no
     * - After end tag: yes
     *
     * @var list<string>
     */
    protected array $oxxo = [
        'pre',
        'textarea',
        'title',
    ];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: yes
     * - After start tag: no
     * - Before end tag: no
     * - After end tag: no
     *
     * @var list<string>
     */
    protected array $oxxx = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: yes
     * - Before end tag: yes
     * - After end tag: yes
     *
     * @var list<string>
     */
    protected array $xooo = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: yes
     * - Before end tag: yes
     * - After end tag: no
     *
     * @var list<string>
     */
    protected array $xoox = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: yes
     * - Before end tag: no
     * - After end tag: yes
     *
     * @var list<string>
     */
    protected array $xoxo = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: yes
     * - Before end tag: no
     * - After end tag: no
     *
     * @var list<string>
     */
    protected array $xoxx = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: no
     * - Before end tag: yes
     * - After end tag: yes
     *
     * @var list<string>
     */
    protected array $xxoo = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: no
     * - Before end tag: yes
     * - After end tag: no
     *
     * @var list<string>
     */
    protected array $xxox = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: no
     * - Before end tag: no
     * - After end tag: yes
     *
     * @var list<string>
     */
    protected array $xxxo = ['br'];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: no
     * - Before end tag: no
     * - After end tag: no
     *
     * @var list<string>
     */
    protected array $xxxx = [
        'a',
        'abbr',
        'b',
        'bdi',
        'bdo',
        'cite',
        'code',
        'data',
        'dfn',
        'em',
        'i',
        'kbd',
        'mark',
        'q',
        's',
        'samp',
        'small',
        'span',
        'strong',
        'sub',
        'sup',
        'time',
        'u',
        'var',
        'wbr',
    ];

    /** @var array<int, string> */
    private array $indentCache = [];

    /** @var array<string, array{0: bool, 1: bool, 2: bool, 3: bool}> */
    private array $elementPatternMap = [];

    protected string $indentStyle = '  ' {
        get {
            return $this->indentStyle;
        }
        set {
            $this->indentCache = [];
            $this->indentStyle = $value;
        }
    }

    /**
     * Insert whitespace into the document so saveHTML() output is indented.
     *
     * @param HTMLDocument $document Document to format.
     */
    public function format(HTMLDocument $document): void
    {
        $root = $document->documentElement;
        if ($root === null) {
            return;
        }

        $this->compilePatterns();

        if ($root->localName === 'html') {
            $this->indentAfterStart($document, $root, 0);
            $this->indentBeforeEnd($document, $root, 0);

            foreach ($root->childNodes as $child) {
                if (!($child instanceof Element)) {
                    continue;
                }
                $this->formatElement($document, $child, 0);
            }
        } else {
            $this->formatElement($document, $root, 0);
        }
    }

    /**
     * Format a comment node (insert newlines/indent per pattern).
     *
     * @param HTMLDocument $document Document to create text nodes from.
     * @param Comment      $comment  Comment node to format.
     * @param int          $depth    Nesting depth (0 = root).
     *
     * @return array{0: bool, 1: bool} Whether indent before start and after end have been applied.
     */
    protected function formatComment(HTMLDocument $document, Comment $comment, int $depth): array
    {
        $this->indentBeforeStart($document, $comment, $depth);
        $this->indentAfterEnd($document, $comment, $depth);
        return [true, true];
    }

    /**
     * Format a single element and its children (insert newlines/indent per pattern).
     *
     * @param HTMLDocument $document Document to create text nodes from.
     * @param Element      $element  Element to format.
     * @param int          $depth    Nesting depth (0 = root).
     *
     * @return array{0: bool, 1: bool} Whether indent before start and after end have been applied.
     */
    protected function formatElement(HTMLDocument $document, Element $element, int $depth): array
    {
        $pattern = $this->elementPatternMap[$element->localName] ?? [true, true, true, true];
        [$beforeStart, $afterStart, $beforeEnd, $afterEnd] = $pattern;

        $hasInnerBs = false;
        $hasInnerAe = false;
        if ($element->hasChildNodes()) {
            foreach ($element->childNodes as $child) {
                [$innerBs, $innerAe] = $this->formatNode($document, $child, $depth + 1);
                $hasInnerBs = $hasInnerBs || $innerBs;
                $hasInnerAe = $hasInnerAe || $innerAe;
            }

            if ($hasInnerBs) {
                $beforeStart = true;
                $afterStart = true;
            }
            if ($hasInnerAe) {
                $beforeEnd = true;
                $afterEnd = true;
            }

            if ($afterStart) {
                $this->indentAfterStart($document, $element, $depth + 1);
            }
            if ($beforeEnd) {
                $this->indentBeforeEnd($document, $element, $depth);
            }
        }

        if ($beforeStart) {
            $this->indentBeforeStart($document, $element, $depth);
        }

        if ($afterEnd) {
            $this->indentAfterEnd($document, $element, $depth);
        }

        return [$beforeStart, $afterEnd];
    }

    /**
     * Format a single node (element or comment) according to the indent pattern.
     *
     * @param HTMLDocument $document Document to create text nodes from.
     * @param Node         $node     Node to format.
     * @param int          $depth    Nesting depth (0 = root).
     *
     * @return array{0: bool, 1: bool} Whether indent before start and after end have been applied.
     */
    protected function formatNode(HTMLDocument $document, Node $node, int $depth): array
    {
        if ($node instanceof Element) {
            return $this->formatElement($document, $node, $depth);
        }
        if ($node instanceof Comment) {
            return $this->formatComment($document, $node, $depth);
        }
        return [false, false];
    }

    /**
     * Returns newline plus indent string for the given depth (cached).
     *
     * @param int $depth Indent level (number of indent units).
     *
     * @return string Newline plus indent string for the given depth.
     */
    protected function getIndent(int $depth): string
    {
        if (!isset($this->indentCache[$depth])) {
            $this->indentCache[$depth] = "\n" . ($depth > 0 ? str_repeat($this->indentStyle, $depth) : '');
        }

        return $this->indentCache[$depth];
    }

    /**
     * Insert newline+indent after the given node's closing tag (position 3: after-end).
     *
     * @param HTMLDocument $document Document to create the text node from.
     * @param Node         $node     Node to insert after (parent is derived from it).
     * @param int          $depth    Indent level (number of indent units).
     */
    protected function indentAfterEnd(HTMLDocument $document, Node $node, int $depth): void
    {
        $parent = $node->parentNode;
        assert($parent !== null);
        // HTMLDocument must not take Text nodes as direct children (e.g. documentElement is not <html>).
        if ($parent instanceof HTMLDocument) {
            return;
        }
        $next = $node->nextSibling;
        if ($next instanceof Text) {
            $next->data = $this->getIndent($depth) . ltrim($next->data);
        } else {
            $node = $document->createTextNode($this->getIndent($depth));
            $parent->insertBefore($node, $next);
        }
    }

    /**
     * Insert newline+indent after the element's open tag, before first child (position 1: after-start).
     *
     * @param HTMLDocument $document Document to create the text node from.
     * @param Element      $element  Element (must have at least one child).
     * @param int          $depth    Indent level (number of indent units).
     */
    protected function indentAfterStart(HTMLDocument $document, Element $element, int $depth): void
    {
        $first = $element->firstChild;
        if ($first instanceof Text) {
            $first->data = $this->getIndent($depth) . ltrim($first->data);
        } else {
            $element->insertBefore($document->createTextNode($this->getIndent($depth)), $first);
        }
    }

    /**
     * Insert newline+indent after the element's last child, before closing tag (position 2: before-end).
     *
     * @param HTMLDocument $document Document to create the text node from.
     * @param Element      $element  Element (must have at least one child).
     * @param int          $depth    Indent level (number of indent units).
     */
    protected function indentBeforeEnd(HTMLDocument $document, Element $element, int $depth): void
    {
        $last = $element->lastChild;
        if ($last instanceof Text) {
            $last->data = rtrim($last->data) . $this->getIndent($depth);
        } else {
            $element->appendChild($document->createTextNode($this->getIndent($depth)));
        }
    }

    /**
     * Insert newline+indent before the given node (position 0: before-start).
     *
     * @param HTMLDocument $document Document to create the text node from.
     * @param Node         $node     Node to insert before (parent is derived from it).
     * @param int          $depth    Indent level (number of indent units).
     */
    protected function indentBeforeStart(HTMLDocument $document, Node $node, int $depth): void
    {
        $parent = $node->parentNode;
        assert($parent !== null);
        if ($parent instanceof HTMLDocument) {
            return;
        }
        $prev = $node->previousSibling;
        if ($prev instanceof Text) {
            $prev->data = rtrim($prev->data) . $this->getIndent($depth);
        } else {
            $parent->insertBefore($document->createTextNode($this->getIndent($depth)), $node);
        }
    }

    /**
     * Compiles the element pattern map.
     */
    private function compilePatterns(): void
    {
        $patterns = [
            $this->xxxx,
            $this->xxxo,
            $this->xxox,
            $this->xxoo,
            $this->xoxx,
            $this->xoxo,
            $this->xoox,
            $this->xooo,
            $this->oxxx,
            $this->oxxo,
            $this->oxox,
            $this->oxoo,
            $this->ooxx,
            $this->ooxo,
            $this->ooox,
        ];

        $this->elementPatternMap = [];
        foreach ($patterns as $i => $pattern) {
            $beforeStart = ($i & 0b1000) !== 0;
            $afterStart  = ($i & 0b0100) !== 0;
            $beforeEnd   = ($i & 0b0010) !== 0;
            $afterEnd    = ($i & 0b0001) !== 0;
            foreach ($pattern as $name) {
                $this->elementPatternMap[$name] = [$beforeStart, $afterStart, $beforeEnd, $afterEnd];
            }
        }
    }
}
