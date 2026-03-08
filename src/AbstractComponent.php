<?php

declare(strict_types=1);

namespace Manychois\Pompom;

use Dom\HTMLDocument;
use Dom\Node;
use Generator;
use LogicException;

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
    private bool $hasChildrenContent = false;
    /** @var null|list<Node> */
    private ?array $resolvedChildren = null;
    private bool $childrenCalled = false;
    /** @var array<string, mixed> */
    private array $regionContents = [];
    /** @var array<string, bool> */
    private array $hasRegionContents = [];
    /** @var list<string> */
    private array $calledRegions = [];
    /** @var array<string, list<Node>> */
    private array $resolvedRegions = [];

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
     * Returns the children content provided by the owner component.
     *
     * @param mixed $fallback Used when no children were provided (yields nodes for this value).
     * @return list<Node> List of DOM nodes representing the children content.
     */
    final protected function children(mixed $fallback = null): array
    {
        if ($this->childrenCalled) {
            throw new LogicException('children() can only be called once.');
        }
        $this->resolveChildren();
        $this->childrenCalled = true;

        $result = $this->resolvedChildren;
        assert($result !== null);
        $this->resolvedChildren = [];

        if (count($result) === 0) {
            $result = [];
            $generator = $this->engine->contentResolver->toNodes($this->document, $fallback);
            foreach ($generator as $node) {
                $result[] = $node;
            }
        }

        return $result;
    }

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
     * Implementations must yield output (mixed); the engine converts each item to a node.
     *
     * @return Generator<int, mixed, mixed, void>
     */
    abstract protected function getContent(): Generator;

    /**
     * Whether the owner component provided any children content.
     *
     * @return boolean
     */
    final protected function hasChildren(): bool
    {
        $this->resolveChildren();
        return $this->hasChildrenContent;
    }

    /**
     * Whether the owner component provided content for the given region.
     *
     * @param string $name Region name.
     * @return boolean
     */
    final protected function hasRegion(string $name): bool
    {
        $this->resolveRegion($name);
        return $this->hasRegionContents[$name];
    }

    /**
     * Returns the content for a named region provided by the owner component.
     *
     * @param string $name     Region name.
     * @param mixed  $fallback Used when no content was provided for the region (yields nodes).
     * @return list<Node> List of DOM nodes representing the region content.
     */
    final protected function region(string $name, mixed $fallback = null): array
    {
        if (in_array($name, $this->calledRegions, true)) {
            throw new LogicException(sprintf('Region "%s" can only be called once.', $name));
        }
        $this->resolveRegion($name);
        $this->calledRegions[] = $name;
        $result = $this->resolvedRegions[$name];
        $this->resolvedRegions[$name] = [];

        if (count($result) === 0) {
            $result = [];
            foreach ($this->engine->contentResolver->toNodes($this->document, $fallback) as $node) {
                $result[] = $node;
            }
        }

        return $result;
    }

    /**
     * Resolves raw children content to a list of nodes (once).
     *
     * @return void
     */
    private function resolveChildren(): void
    {
        if ($this->resolvedChildren === null) {
            $nodes = [];
            $generator = $this->engine->contentResolver->toNodes($this->document, $this->childrenContent);
            foreach ($generator as $node) {
                $nodes[] = $node;
            }
            $this->resolvedChildren = $nodes;
            $this->childrenContent = null;
            $this->hasChildrenContent = count($nodes) > 0;
        }
    }

    /**
     * Resolves raw region content for the given name to a list of nodes (once).
     *
     * @param string $name Region name.
     * @return void
     */
    private function resolveRegion(string $name): void
    {
        if (!\array_key_exists($name, $this->resolvedRegions)) {
            $nodes = [];
            $generator = $this->engine->contentResolver->toNodes($this->document, $this->regionContents[$name] ?? null);
            foreach ($generator as $node) {
                $nodes[] = $node;
            }
            $this->resolvedRegions[$name] = $nodes;
            unset($this->regionContents[$name]);
            $this->hasRegionContents[$name] = count($nodes) > 0;
        }
    }
}
