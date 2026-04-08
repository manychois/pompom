<?php

declare(strict_types=1);

namespace Manychois\PompomTests;

use Dom\Element;
use Dom\HTMLDocument;
use Manychois\Pompom\Engine;
use Manychois\Pompom\Internal\NodeUtility;
use Manychois\PompomTests\Fixtures\RejectingComponentResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Tests for {@see NodeUtility}.
 */
final class NodeUtilityTest extends TestCase
{
    private function utility(): NodeUtility
    {
        $document = HTMLDocument::createEmpty();
        $engine = new Engine(new RejectingComponentResolver());

        return new NodeUtility($document, $engine->contentResolver);
    }

    #[Test]
    public function create_element_appends_resolved_children(): void
    {
        $u = $this->utility();
        $el = $u->createElement('p', [], ['a', 'b']);
        self::assertSame('p', $el->localName);
        self::assertSame('ab', $el->textContent);
    }

    #[Test]
    public function create_element_treats_string_attributes_as_class(): void
    {
        $u = $this->utility();
        $el = $u->createElement('div', 'foo bar');
        self::assertSame('foo bar', $el->getAttribute('class'));
    }

    #[Test]
    public function change_attributes_applies_scalar_pairs(): void
    {
        $u = $this->utility();
        $el = $u->createElement('div', ['id' => 'x', 'title' => 't']);
        self::assertSame('x', $el->getAttribute('id'));
        self::assertSame('t', $el->getAttribute('title'));
    }

    #[Test]
    public function create_comment_returns_comment_in_document(): void
    {
        $u = $this->utility();
        $comment = $u->createComment('note');
        self::assertSame('note', $comment->data);
    }

    #[Test]
    public function create_doctype_returns_imported_document_type(): void
    {
        $u = $this->utility();
        $dt = $u->createDoctype('html');
        self::assertSame('html', $dt->name);
    }

    #[Test]
    public function parse_html_returns_fragment_with_nodes(): void
    {
        $u = $this->utility();
        $fragment = $u->parseHtml('<span>hi</span>');
        self::assertGreaterThan(0, $fragment->childNodes->length);
    }

    #[Test]
    public function parse_element_returns_first_element_child(): void
    {
        $u = $this->utility();
        $el = $u->parseElement('<strong>x</strong>');
        self::assertSame('strong', $el->localName);
        self::assertSame('x', $el->textContent);
    }

    #[Test]
    public function parse_element_throws_when_no_element_parsed(): void
    {
        $u = $this->utility();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to parse HTML into an element.');
        $u->parseElement('plain text only');
    }

    #[Test]
    public function loop_ancestor_elements_yields_parents(): void
    {
        $u = $this->utility();
        $root = $u->createElement('div', [], $u->createElement('section', [], $inner = $u->createElement('i')));
        $ancestors = iterator_to_array($u->loopAncestorElements($inner), false);
        self::assertCount(2, $ancestors);
        self::assertSame('section', $ancestors[0]->localName);
        self::assertSame('div', $ancestors[1]->localName);
    }

    #[Test]
    public function loop_descendant_nodes_visits_depth_first(): void
    {
        $u = $this->utility();
        $root = $u->createElement('div', [], [
            $u->createElement('span', [], 'a'),
            $u->createElement('b', [], 'c'),
        ]);
        $tags = [];
        foreach ($u->loopDescendantNodes($root) as $n) {
            if ($n instanceof Element) {
                $tags[] = $n->localName;
            }
        }
        self::assertSame(['span', 'b'], $tags);
    }

    #[Test]
    public function loop_descendant_nodes_respects_filter(): void
    {
        $u = $this->utility();
        $root = $u->createElement('div', [], [
            $u->createElement('span', ['class' => 'keep']),
            $u->createElement('span', ['class' => 'drop']),
        ]);
        $classes = [];
        foreach ($u->loopDescendantNodes(
            $root,
            static fn ($n) => $n instanceof Element && $n->classList->contains('keep'),
        ) as $n) {
            if ($n instanceof Element) {
                $classes[] = $n->getAttribute('class');
            }
        }
        self::assertSame(['keep'], $classes);
    }

    #[Test]
    public function loop_descendant_elements_yields_only_elements_with_indices(): void
    {
        $u = $this->utility();
        $root = $u->createElement('ul', [], [
            $u->createElement('li', [], '1'),
            $u->createElement('li', [], '2'),
        ]);
        $names = [];
        foreach ($u->loopDescendantElements($root) as $i => $el) {
            $names[$i] = $el->localName;
        }
        self::assertSame([0 => 'li', 1 => 'li'], $names);
    }
}
