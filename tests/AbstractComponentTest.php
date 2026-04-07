<?php

declare(strict_types=1);

namespace Manychois\PompomTests;

use Dom\HTMLDocument;
use Generator;
use LogicException;
use Manychois\Pompom\AbstractComponent;
use Manychois\Pompom\Engine;
use Manychois\PompomTests\Fixtures\ChildrenEchoComponent;
use Manychois\PompomTests\Fixtures\MapComponentResolver;
use Manychois\PompomTests\Fixtures\MergeChildComponent;
use Manychois\PompomTests\Fixtures\MergeParentComponent;
use Manychois\PompomTests\Fixtures\RegionEchoComponent;
use Manychois\PompomTests\Fixtures\RejectingComponentResolver;
use Manychois\PompomTests\Fixtures\YieldsChildComponent;
use Manychois\PompomTests\Fixtures\YieldsRegionChildComponent;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see AbstractComponent}.
 */
final class AbstractComponentTest extends TestCase
{
    public function test_render_yields_resolved_text_nodes(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->createEngine();

        $component = new class ($document, $engine) extends AbstractComponent {
            /**
             * @return Generator<int, mixed, mixed, void>
             */
            protected function getContent(): Generator
            {
                yield 'hello';
                yield ' ';
                yield 'world';
            }
        };

        $nodes = iterator_to_array($component->render(), false);
        self::assertCount(3, $nodes);
        self::assertSame('hello', $nodes[0]->textContent);
        self::assertSame(' ', $nodes[1]->textContent);
        self::assertSame('world', $nodes[2]->textContent);
    }

    public function test_render_leaves_only_custom_keys_in_props_for_get_content(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->createEngine();

        $component = new class ($document, $engine) extends AbstractComponent {
            /**
             * @return Generator<int, mixed, mixed, void>
             */
            protected function getContent(): Generator
            {
                yield json_encode($this->props, JSON_THROW_ON_ERROR);
            }
        };

        $props = [
            AbstractComponent::PROP_CHILDREN => 'ignored-in-props',
            AbstractComponent::PROP_REGIONS  => ['slot' => 'ignored'],
            'key'                              => 'value',
        ];
        $nodes = iterator_to_array($component->render($props), false);
        self::assertCount(1, $nodes);
        self::assertSame('{"key":"value"}', $nodes[0]->textContent);
    }

    public function test_children_yields_nodes_from_prop_children_when_provided(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->createEngine();

        $component = new class ($document, $engine) extends AbstractComponent {
            /**
             * @return Generator<int, mixed, mixed, void>
             */
            protected function getContent(): Generator
            {
                foreach ($this->children() as $node) {
                    yield $node;
                }
            }
        };

        $nodes = iterator_to_array(
            $component->render([AbstractComponent::PROP_CHILDREN => 'inner']),
            false,
        );
        self::assertCount(1, $nodes);
        self::assertSame('inner', $nodes[0]->textContent);
    }

    public function test_children_yields_fallback_nodes_when_prop_children_absent(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->createEngine();

        $component = new class ($document, $engine) extends AbstractComponent {
            /**
             * @return Generator<int, mixed, mixed, void>
             */
            protected function getContent(): Generator
            {
                foreach ($this->children('fallback') as $node) {
                    yield $node;
                }
            }
        };

        $nodes = iterator_to_array($component->render([]), false);
        self::assertCount(1, $nodes);
        self::assertSame('fallback', $nodes[0]->textContent);
    }

    public function test_children_throws_logic_exception_when_called_twice(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->createEngine();

        $component = new class ($document, $engine) extends AbstractComponent {
            /**
             * @return Generator<int, mixed, mixed, void>
             */
            protected function getContent(): Generator
            {
                $this->children();
                $this->children();
                yield 'x';
            }
        };

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('children() can only be called once.');
        iterator_to_array($component->render([]), false);
    }

    public function test_has_children_returns_bool_matching_prop_children_presence(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->createEngine();

        $with = new class ($document, $engine) extends AbstractComponent {
            /**
             * @return Generator<int, mixed, mixed, void>
             */
            protected function getContent(): Generator
            {
                yield $this->hasChildren() ? 'yes' : 'no';
            }
        };
        $without = new class ($document, $engine) extends AbstractComponent {
            /**
             * @return Generator<int, mixed, mixed, void>
             */
            protected function getContent(): Generator
            {
                yield $this->hasChildren() ? 'yes' : 'no';
            }
        };

        $withNodes = iterator_to_array(
            $with->render([AbstractComponent::PROP_CHILDREN => 'x']),
            false,
        );
        $withoutNodes = iterator_to_array($without->render([]), false);

        self::assertSame('yes', $withNodes[0]->textContent);
        self::assertSame('no', $withoutNodes[0]->textContent);
    }

    public function test_region_yields_nodes_from_named_region_when_provided(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->createEngine();

        $component = new class ($document, $engine) extends AbstractComponent {
            /**
             * @return Generator<int, mixed, mixed, void>
             */
            protected function getContent(): Generator
            {
                foreach ($this->region('main') as $node) {
                    yield $node;
                }
            }
        };

        $nodes = iterator_to_array(
            $component->render([
                AbstractComponent::PROP_REGIONS => ['main' => 'sidebar text'],
            ]),
            false,
        );
        self::assertCount(1, $nodes);
        self::assertSame('sidebar text', $nodes[0]->textContent);
    }

    public function test_region_yields_fallback_nodes_when_region_absent(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->createEngine();

        $component = new class ($document, $engine) extends AbstractComponent {
            /**
             * @return Generator<int, mixed, mixed, void>
             */
            protected function getContent(): Generator
            {
                foreach ($this->region('main', 'default') as $node) {
                    yield $node;
                }
            }
        };

        $nodes = iterator_to_array($component->render([]), false);
        self::assertCount(1, $nodes);
        self::assertSame('default', $nodes[0]->textContent);
    }

    public function test_region_throws_logic_exception_when_called_twice_for_same_name(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->createEngine();

        $component = new class ($document, $engine) extends AbstractComponent {
            /**
             * @return Generator<int, mixed, mixed, void>
             */
            protected function getContent(): Generator
            {
                $this->region('main');
                $this->region('main');
                yield 'x';
            }
        };

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Region "main" can only be called once.');
        iterator_to_array($component->render([]), false);
    }

    public function test_has_region_returns_bool_matching_named_region_presence(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->createEngine();

        $component = new class ($document, $engine) extends AbstractComponent {
            /**
             * @return Generator<int, mixed, mixed, void>
             */
            protected function getContent(): Generator
            {
                $a = $this->hasRegion('a') ? '1' : '0';
                $b = $this->hasRegion('b') ? '1' : '0';
                yield $a . $b;
            }
        };

        $nodes = iterator_to_array(
            $component->render([
                AbstractComponent::PROP_REGIONS => ['a' => 'content'],
            ]),
            false,
        );
        self::assertSame('10', $nodes[0]->textContent);
    }

    public function test_component_merges_parent_props_when_spread_placeholder_in_props_array(): void
    {
        $document = HTMLDocument::createEmpty();
        $map = [
            'merge-parent' => MergeParentComponent::class,
            'merge-child'  => MergeChildComponent::class,
        ];
        $engine = new Engine(new MapComponentResolver($map));

        $component = new MergeParentComponent($document, $engine);
        $nodes = iterator_to_array($component->render(['fromParent' => 1]), false);

        self::assertCount(1, $nodes);
        $decoded = json_decode($nodes[0]->textContent, true, 512, JSON_THROW_ON_ERROR);
        self::assertSame(['fromParent' => 1, 'extra' => 2], $decoded);
    }

    public function test_component_child_receives_prop_children_after_engine_render(): void
    {
        $map = [
            'root'  => YieldsChildComponent::class,
            'child' => ChildrenEchoComponent::class,
        ];
        $engine = new Engine(new MapComponentResolver($map));
        $document = $engine->render('root', []);

        self::assertStringContainsString('inner-from-parent', $document->saveHtml());
    }

    public function test_component_child_receives_prop_regions_after_engine_render(): void
    {
        $map = [
            'root'            => YieldsRegionChildComponent::class,
            'region-consumer' => RegionEchoComponent::class,
        ];
        $engine = new Engine(new MapComponentResolver($map));
        $document = $engine->render('root', []);

        self::assertStringContainsString('region-from-parent', $document->saveHtml());
    }

    private function createEngine(): Engine
    {
        return new Engine(new RejectingComponentResolver());
    }
}
