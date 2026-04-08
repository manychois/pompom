<?php

declare(strict_types=1);

namespace Manychois\PompomTests;

use Dom\HTMLDocument;
use Manychois\Pompom\AbstractComponent;
use Manychois\Pompom\Engine;
use Manychois\Pompom\Internal\ComponentBuilder;
use Manychois\Pompom\Internal\Psr4ComponentResolver;
use Manychois\PompomTests\Fixtures\ChildrenEchoComponent;
use Manychois\PompomTests\Fixtures\MapComponentResolver;
use Manychois\PompomTests\Fixtures\RegionEchoComponent;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see ComponentBuilder}.
 */
final class ComponentBuilderTest extends TestCase
{
    private function psr4Engine(): Engine
    {
        return new Engine(new Psr4ComponentResolver([
            'Manychois\\PompomTests\\Fixtures\\Psr4\\' => __DIR__ . '/Fixtures/Psr4',
        ]));
    }

    #[Test]
    public function to_nodes_yields_output_from_named_component(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $builder = new ComponentBuilder('hello-page', []);
        $nodes = iterator_to_array($engine->contentResolver->toNodes($document, $builder), false);
        self::assertCount(1, $nodes);
        self::assertSame('hello-psr4', $nodes[0]->textContent);
    }

    #[Test]
    public function with_children_overrides_prop_children_key_in_props(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = new Engine(new MapComponentResolver([
            'echo-child' => ChildrenEchoComponent::class,
        ]));
        $builder = (new ComponentBuilder('echo-child', [
            AbstractComponent::PROP_CHILDREN => 'from-props',
        ]))->withChildren('from-builder');
        $nodes = iterator_to_array($engine->contentResolver->toNodes($document, $builder), false);
        self::assertCount(1, $nodes);
        self::assertSame('from-builder', $nodes[0]->textContent);
    }

    #[Test]
    public function without_with_children_uses_prop_children_from_props(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = new Engine(new MapComponentResolver([
            'echo-child' => ChildrenEchoComponent::class,
        ]));
        $builder = new ComponentBuilder('echo-child', [
            AbstractComponent::PROP_CHILDREN => 'from-default-prop',
        ]);
        $nodes = iterator_to_array($engine->contentResolver->toNodes($document, $builder), false);
        self::assertCount(1, $nodes);
        self::assertSame('from-default-prop', $nodes[0]->textContent);
    }

    #[Test]
    public function with_region_overrides_same_key_from_prop_regions(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = new Engine(new MapComponentResolver([
            'echo-region' => RegionEchoComponent::class,
        ]));
        $builder = (new ComponentBuilder('echo-region', [
            AbstractComponent::PROP_REGIONS => ['slot' => 'from-props'],
        ]))->withRegion('slot', 'from-builder');
        $nodes = iterator_to_array($engine->contentResolver->toNodes($document, $builder), false);
        self::assertCount(1, $nodes);
        self::assertSame('from-builder', $nodes[0]->textContent);
    }
}
