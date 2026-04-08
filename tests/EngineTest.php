<?php

declare(strict_types=1);

namespace Manychois\PompomTests;

use Dom\HTMLDocument;
use Manychois\Pompom\Engine;
use Manychois\Pompom\Internal\ContentResolver;
use Manychois\Pompom\Internal\Psr4ComponentResolver;
use Manychois\PompomTests\Fixtures\Psr4\HelloPage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see Engine}.
 */
final class EngineTest extends TestCase
{
    private function engine(): Engine
    {
        $baseDir = __DIR__ . '/Fixtures/Psr4';

        return new Engine(new Psr4ComponentResolver([
            'Manychois\\PompomTests\\Fixtures\\Psr4\\' => $baseDir,
        ]));
    }

    #[Test]
    public function render_appends_resolved_nodes_to_empty_document(): void
    {
        $engine = $this->engine();
        $document = $engine->render('hello-page', []);
        self::assertInstanceOf(HTMLDocument::class, $document);
        $html = $document->saveHtml();
        self::assertStringContainsString('hello-psr4', $html);
    }

    #[Test]
    public function get_component_returns_instance_with_same_document_and_engine(): void
    {
        $engine = $this->engine();
        $document = HTMLDocument::createEmpty();
        $component = $engine->getComponent('hello-page', $document);
        self::assertInstanceOf(HelloPage::class, $component);
        self::assertSame($document, $component->document);
        self::assertSame($engine, $component->engine);
    }

    #[Test]
    public function content_resolver_defaults_to_content_resolver(): void
    {
        $engine = $this->engine();
        self::assertInstanceOf(ContentResolver::class, $engine->contentResolver);
    }
}
