<?php

declare(strict_types=1);

namespace Manychois\Pompom;

use Dom\Element;
use Dom\HTMLDocument;
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
     * @var list<string>
     */
    protected array $ooox = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: yes
     * - After start tag: yes
     * - Before end tag: no
     * - After end tag: yes
     * @var list<string>
     */
    protected array $ooxo = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: yes
     * - After start tag: yes
     * - Before end tag: no
     * - After end tag: no
     * @var list<string>
     */
    protected array $ooxx = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: yes
     * - After start tag: no
     * - Before end tag: yes
     * - After end tag: yes
     * @var list<string>
     */
    protected array $oxoo = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: yes
     * - After start tag: no
     * - Before end tag: yes
     * - After end tag: no
     * @var list<string>
     */
    protected array $oxox = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: yes
     * - After start tag: no
     * - Before end tag: no
     * - After end tag: yes
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
     * @var list<string>
     */
    protected array $oxxx = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: yes
     * - Before end tag: yes
     * - After end tag: yes
     * @var list<string>
     */
    protected array $xooo = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: yes
     * - Before end tag: yes
     * - After end tag: no
     * @var list<string>
     */
    protected array $xoox = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: yes
     * - Before end tag: no
     * - After end tag: yes
     * @var list<string>
     */
    protected array $xoxo = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: yes
     * - Before end tag: no
     * - After end tag: no
     * @var list<string>
     */
    protected array $xoxx = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: no
     * - Before end tag: yes
     * - After end tag: yes
     * @var list<string>
     */
    protected array $xxoo = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: no
     * - Before end tag: yes
     * - After end tag: no
     * @var list<string>
     */
    protected array $xxox = [];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: no
     * - Before end tag: no
     * - After end tag: yes
     * @var list<string>
     */
    protected array $xxxo = ['br'];

    /**
     * List of elements that use these indentation styles:
     * - Before start tag: no
     * - After start tag: no
     * - Before end tag: no
     * - After end tag: no
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

    protected string $indent = '  ';

    /**
     * Insert whitespace into the document so saveHTML() output is indented.
     *
     * @param HTMLDocument $document Document to format.
     * @return void
     */
    public function format(HTMLDocument $document): void
    {
        $root = $document->documentElement;
        if ($root === null) {
            return;
        }

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
     * @param string $localName Element local name (e.g. "div", "span").
     * @return array{0: bool, 1: bool, 2: bool, 3: bool} 0=before-start, 1=after-start, 2=before-end, 3=after-end.
     */
    protected function getPattern(string $localName): array
    {
        if (in_array($localName, $this->xxxx, true)) {
            return [false, false, false, false];
        }
        if (in_array($localName, $this->xxxo, true)) {
            return [false, false, false, true];
        }
        if (in_array($localName, $this->xxox, true)) {
            return [false, false, true, false];
        }
        if (in_array($localName, $this->xxoo, true)) {
            return [false, false, true, true];
        }
        if (in_array($localName, $this->xoxx, true)) {
            return [false, true, false, false];
        }
        if (in_array($localName, $this->xoxo, true)) {
            return [false, true, false, true];
        }
        if (in_array($localName, $this->xoox, true)) {
            return [false, true, true, false];
        }
        if (in_array($localName, $this->xooo, true)) {
            return [false, true, true, true];
        }
        if (in_array($localName, $this->oxxx, true)) {
            return [true, false, false, false];
        }
        if (in_array($localName, $this->oxxo, true)) {
            return [true, false, false, true];
        }
        if (in_array($localName, $this->oxox, true)) {
            return [true, false, true, false];
        }
        if (in_array($localName, $this->oxoo, true)) {
            return [true, false, true, true];
        }
        if (in_array($localName, $this->ooxx, true)) {
            return [true, true, false, false];
        }
        if (in_array($localName, $this->ooxo, true)) {
            return [true, true, false, true];
        }
        if (in_array($localName, $this->ooox, true)) {
            return [true, true, true, false];
        }

        return [true, true, true, true];
    }

    /**
     * Format a single element and its children (insert newlines/indent per pattern).
     *
     * @param HTMLDocument $document Document to create text nodes from.
     * @param Element      $element  Element to format.
     * @param integer      $depth    Nesting depth (0 = root).
     * @return void
     */
    private function formatElement(HTMLDocument $document, Element $element, int $depth): void
    {
        $pattern = $this->getPattern($element->localName);
        [$beforeStart, $afterStart, $beforeEnd, $afterEnd] = $pattern;

        if ($beforeStart) {
            $this->indentBeforeStart($document, $element, $depth);
        }
        if ($element->hasChildNodes()) {
            if ($afterStart) {
                $this->indentAfterStart($document, $element, $depth + 1);
            }
            foreach ($element->childNodes as $child) {
                if (!($child instanceof Element)) {
                    continue;
                }
                $this->formatElement($document, $child, $depth + 1);
            }
            if ($beforeEnd) {
                $this->indentBeforeEnd($document, $element, $depth);
            }
        }
        if ($afterEnd) {
            $this->indentAfterEnd($document, $element, $depth);
        }
    }

    /**
     * Insert newline+indent before the given element (position 0: before-start).
     *
     * @param HTMLDocument $document Document to create the text node from.
     * @param Element      $element  Element to insert before (parent is derived from it).
     * @param integer      $depth    Indent level (number of indent units).
     * @return void
     */
    protected function indentBeforeStart(HTMLDocument $document, Element $element, int $depth): void
    {
        $parent = $element->parentNode;
        if ($parent === null) {
            return;
        }
        $prev = $element->previousSibling;
        $indent = "\n" . str_repeat($this->indent, $depth);
        if ($prev instanceof Text) {
            $prev->data = rtrim($prev->data) . $indent;
        } else {
            $parent->insertBefore($document->createTextNode($indent), $element);
        }
    }

    /**
     * Insert newline+indent after the element's open tag, before first child (position 1: after-start).
     *
     * @param HTMLDocument $document Document to create the text node from.
     * @param Element      $element  Element (must have at least one child).
     * @param integer      $depth    Indent level (number of indent units).
     * @return void
     */
    protected function indentAfterStart(HTMLDocument $document, Element $element, int $depth): void
    {
        $indent = "\n" . str_repeat($this->indent, $depth);
        $first = $element->firstChild;
        if ($first instanceof Text) {
            $first->data = $indent . ltrim($first->data);
        } else {
            $element->insertBefore($document->createTextNode($indent), $first);
        }
    }

    /**
     * Insert newline+indent after the element's last child, before closing tag (position 2: before-end).
     *
     * @param HTMLDocument $document Document to create the text node from.
     * @param Element      $element  Element (must have at least one child).
     * @param integer      $depth    Indent level (number of indent units).
     * @return void
     */
    protected function indentBeforeEnd(HTMLDocument $document, Element $element, int $depth): void
    {
        $indent = "\n" . str_repeat($this->indent, $depth);
        $last = $element->lastChild;
        if ($last instanceof Text) {
            $last->data = rtrim($last->data) . $indent;
        } else {
            $element->appendChild($document->createTextNode($indent));
        }
    }

    /**
     * Insert newline+indent after the given element's closing tag (position 3: after-end).
     *
     * @param HTMLDocument $document Document to create the text node from.
     * @param Element      $element  Element to insert after (parent is derived from it).
     * @param integer      $depth    Indent level (number of indent units).
     * @return void
     */
    protected function indentAfterEnd(HTMLDocument $document, Element $element, int $depth): void
    {
        $parent = $element->parentNode;
        if ($parent === null) {
            return;
        }
        $indent = "\n" . str_repeat($this->indent, $depth);
        $next = $element->nextSibling;
        if ($next instanceof Text) {
            $next->data = $indent . ltrim($next->data);
        } else {
            $node = $document->createTextNode($indent);
            $parent->insertBefore($node, $next);
        }
    }
}
