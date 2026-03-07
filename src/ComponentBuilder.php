<?php

declare(strict_types=1);

namespace Manychois\Pompom;

use Dom\HTMLDocument;
use Generator;

/**
 * Builds a reference to a component by name and props; use withChildren() / withRegion(),
 * then pass to content resolver via toNodes(engine, document).
 */
final class ComponentBuilder implements NodableInterface
{
    private mixed $children = null;

    /** @var array<string, mixed> */
    private array $regions = [];

    /**
     * @param string               $name  Component name (resolved by the engine).
     * @param array<string, mixed> $props Default props for the component.
     */
    public function __construct(
        private readonly string $name,
        private readonly array $props,
    ) {
    }

    /**
     * @param mixed $content Child content (resolved when rendering).
     * @return self
     */
    public function withChildren(mixed $content): self
    {
        $this->children = $content;
        return $this;
    }

    /**
     * @param string $name    Region name.
     * @param mixed  $content Region content (resolved when rendering).
     * @return self
     */
    public function withRegion(string $name, mixed $content): self
    {
        $this->regions[$name] = $content;
        return $this;
    }

    /**
     * @param Engine       $engine   Engine to resolve the component and content.
     * @param HTMLDocument $document Document to create/import nodes in.
     * @return Generator<int, \Dom\Node, mixed, void>
     */
    public function toNodes(Engine $engine, HTMLDocument $document): Generator
    {
        $props = array_merge($this->props, [
            AbstractComponent::PROP_CHILDREN => $this->children,
            AbstractComponent::PROP_REGIONS => $this->regions,
        ]);

        $component = $engine->getComponent($this->name, $document);
        foreach ($component->render($props) as $content) {
            yield from $engine->contentResolver->toNodes($document, $content);
        }
    }
}
