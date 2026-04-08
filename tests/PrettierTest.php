<?php

declare(strict_types=1);

namespace Manychois\PompomTests;

use Dom\HTMLDocument;
use Dom\Text;
use Manychois\Pompom\Prettier;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see Prettier}.
 */
final class PrettierTest extends TestCase
{
    #[Test]
    public function format_does_nothing_when_document_has_no_root_element(): void
    {
        $document = HTMLDocument::createEmpty();
        $prettier = new Prettier();
        $prettier->format($document);
        self::assertNull($document->documentElement);
    }

    #[Test]
    public function format_inserts_newlines_for_nested_block_elements(): void
    {
        $document = HTMLDocument::createEmpty();
        $html = $document->createElement('html');
        $body = $document->createElement('body');
        $outer = $document->createElement('div');
        $inner = $document->createElement('div');
        $outer->appendChild($inner);
        $body->appendChild($outer);
        $html->appendChild($body);
        $document->appendChild($html);

        $prettier = new Prettier();
        $prettier->format($document);
        $output = $document->saveHtml();
        self::assertStringContainsString("\n", $output);
        self::assertStringContainsString('<div>', $output);
    }

    #[Test]
    public function format_uses_subclass_tab_indent_style(): void
    {
        $document = HTMLDocument::createEmpty();
        $html = $document->createElement('html');
        $body = $document->createElement('body');
        $body->appendChild($document->createElement('div'));
        $html->appendChild($body);
        $document->appendChild($html);

        $prettier = new class () extends Prettier {
            public function __construct()
            {
                $this->indentStyle = "\t";
            }
        };
        $prettier->format($document);
        $output = $document->saveHtml();
        self::assertMatchesRegularExpression('/\t+/', $output);
    }

    #[Test]
    public function format_applies_when_document_element_is_not_html(): void
    {
        $document = HTMLDocument::createEmpty();
        $root = $document->createElement('section');
        $root->appendChild($document->createElement('p'));
        $document->appendChild($root);

        $prettier = new Prettier();
        $prettier->format($document);

        self::assertSame('section', $document->documentElement?->localName);
        $html = $document->saveHtml();
        self::assertStringContainsString('<section>', $html);
        self::assertStringContainsString('<p>', $html);
        self::assertStringContainsString("\n", $html);
    }

    #[Test]
    public function format_inserts_whitespace_around_comment_nodes(): void
    {
        $document = HTMLDocument::createEmpty();
        $html = $document->createElement('html');
        $body = $document->createElement('body');
        $wrap = $document->createElement('div');
        $wrap->appendChild($document->createComment('note'));
        $wrap->appendChild($document->createElement('em'));
        $body->appendChild($wrap);
        $html->appendChild($body);
        $document->appendChild($html);

        $prettier = new Prettier();
        $prettier->format($document);
        self::assertStringContainsString('note', $document->saveHtml());
        self::assertStringContainsString("\n", $document->saveHtml());
    }

    #[Test]
    public function format_skips_non_element_children_under_html_root(): void
    {
        $document = HTMLDocument::createEmpty();
        $html = $document->createElement('html');
        $html->insertBefore($document->createTextNode(' '), $html->firstChild);
        $body = $document->createElement('body');
        $html->appendChild($body);
        $document->appendChild($html);

        $prettier = new Prettier();
        $prettier->format($document);
        self::assertInstanceOf(Text::class, $html->firstChild);
    }

    #[Test]
    public function indent_helpers_merge_with_adjacent_text_siblings(): void
    {
        $document = HTMLDocument::createEmpty();
        $html = $document->createElement('html');
        $body = $document->createElement('body');
        $block = $document->createElement('div');
        $block->appendChild($document->createTextNode('x'));
        $inner = $document->createElement('span');
        $inner->appendChild($document->createTextNode('y'));
        $block->appendChild($inner);
        $body->appendChild($block);
        $html->appendChild($body);
        $document->appendChild($html);

        $prettier = new Prettier();
        $prettier->format($document);
        $out = $document->saveHtml();
        self::assertStringContainsString('x', $out);
        self::assertStringContainsString('y', $out);
        self::assertStringContainsString("\n", $out);
    }
}
