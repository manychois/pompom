<?php

declare(strict_types=1);

namespace Manychois\Pompom;

use Dom\HTMLDocument;
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
     * @return Generator<int, mixed, mixed, void>
     */
    abstract public function render(array $props = []): Generator;

    /**
     * @param string               $name  Component name (resolved by the engine).
     * @param array<string, mixed> $props Default props for the component.
     * @return ComponentBuilder
     */
    final protected function component(string $name, array $props = []): ComponentBuilder
    {
        return new ComponentBuilder($name, $props);
    }

    /**
     * Returns the children content from render props (set by ComponentBuilder).
     *
     * @param array<string, mixed> $props Render props passed to run().
     * @return mixed Children content or null.
     */
    final protected function placeChildren(array $props): mixed
    {
        return $props[self::PROP_CHILDREN] ?? null;
    }

    /**
     * Returns the content for a named region from render props (set by ComponentBuilder).
     *
     * @param string               $name  Region name (e.g. 'head').
     * @param array<string, mixed> $props Render props passed to run().
     * @return mixed Region content or null.
     */
    final protected function placeRegion(string $name, array $props): mixed
    {
        $regions = $props[self::PROP_REGIONS] ?? [];
        assert(is_array($regions));
        return $regions[$name] ?? null;
    }
}
