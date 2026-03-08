<?php

declare(strict_types=1);

namespace Manychois\Pompom;

use Dom\HTMLDocument;
use Dom\Node;
use Generator;

/**
 * Base class for all Pompom DOM components.
 *
 * Every component works with a shared Dom\HTMLDocument and is responsible
 * for yielding output (mixed; the engine converts each item to Dom\Node via ContentResolver).
 * Use NodeFactory (optional) for createElement() and createText().
 */
abstract class AbstractComponent
{
    public const PROP_CHILDREN = __CLASS__ . '::CHILDREN';
    public const PROP_REGIONS = __CLASS__ . '::REGIONS';

    protected readonly NodeFactory $nodeFactory;
    /** @var array<string, mixed> */
    protected array $props = [];
    private mixed $childrenContent = null;
    /** @var array<string, mixed> */
    private array $regionContents = [];

    /**
     * @param HTMLDocument $document The DOM document for this component.
     * @param Engine       $engine   Engine that is rendering this component.
     */
    public function __construct(
        public readonly HTMLDocument $document,
        public readonly Engine $engine,
    ) {
        $this->nodeFactory = new NodeFactory($this->document, $this->engine->contentResolver);
    }

    /**
     * Implementations must yield output (mixed); the engine converts each item to a node.
     *
     * @param array<string, mixed> $props Render-time properties (e.g. from the engine).
     * @return Generator<int, Node, mixed, void>
     */
    final public function render(array $props = []): Generator
    {
        $this->childrenContent = $props[self::PROP_CHILDREN] ?? null;
        // @phpstan-ignore assign.propertyType
        $this->regionContents = $props[self::PROP_REGIONS] ?? [];
        unset($props[self::PROP_CHILDREN], $props[self::PROP_REGIONS]);
        $this->props = $props;
        foreach ($this->getContent() as $content) {
            yield from $this->engine->contentResolver->toNodes($this->document, $content);
        }
    }

    /**
     * Implementations must yield output (mixed); the engine converts each item to a node.
     *
     * @return Generator<int, mixed, mixed, void>
     */
    abstract protected function getContent(): Generator;

    /**
     * @param string       $name     Component name (resolved by the engine).
     * @param array<mixed> $props    Default props for the component.
     * @param mixed        $children Children content or null.
     * @param mixed        $regions  Regions content or null.
     * @return ComponentBuilder
     */
    final protected function component(
        string $name,
        array $props = [],
        mixed $children = null,
        mixed $regions = [],
    ): ComponentBuilder {
        if (in_array('...', $props, true)) {
            unset($props['...']);
            $props = array_merge($this->props, $props);
        }
        $props[self::PROP_CHILDREN] = $children;
        $props[self::PROP_REGIONS] = $regions;

        /**
         * @var array<string, mixed> $props
         */
        return new ComponentBuilder($name, $props);
    }

    /**
     * Returns the children content provided by the owner component.
     *
     * @return list<Node> List of DOM nodes representing the children content.
     */
    final protected function children(): array
    {
        if ($this->childrenContent === null) {
            return [];
        }
        $nodes = [];
        foreach ($this->engine->contentResolver->toNodes($this->document, $this->childrenContent) as $node) {
            $nodes[] = $node;
        }
        $this->childrenContent = null;
        return $nodes;
    }

    /**
     * Returns the content for a named region provided by the owner component.
     *
     * @param string $name Region name.
     * @return list<Node> List of DOM nodes representing the region content.
     */
    final protected function region(string $name): array
    {
        $content = $this->regionContents[$name] ?? null;
        if ($content === null) {
            return [];
        }
        $nodes = [];
        foreach ($this->engine->contentResolver->toNodes($this->document, $content) as $node) {
            $nodes[] = $node;
        }
        unset($this->regionContents[$name]);
        return $nodes;
    }
}
