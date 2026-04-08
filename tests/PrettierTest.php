<?php

declare(strict_types=1);

namespace Manychois\PompomTests;

use Dom\HTMLDocument;
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
}
