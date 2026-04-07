<?php

declare(strict_types=1);

namespace Manychois\Pompom\Internal;

use Dom\HTMLDocument;
use Generator;
use Manychois\Pompom\AbstractComponent;
use Manychois\Pompom\Engine;
use Manychois\Pompom\NodableInterface as INodable;

/**
 * Builds a reference to a component by name and props; use withChildren() / withRegion(),
 * then pass to content resolver via toNodes(engine, document).
 */
class ComponentBuilder implements INodable
{
    private mixed $children = null;

    private bool $childrenExplicit = false;

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
     *
     * @return self The current instance for method chaining.
     */
    public function withChildren(mixed $content): self
    {
        $this->children = $content;
        $this->childrenExplicit = true;
        return $this;
    }

    /**
     * @param string $name    Region name.
     * @param mixed  $content Region content (resolved when rendering).
     *
     * @return self The current instance for method chaining.
     */
    public function withRegion(string $name, mixed $content): self
    {
        $this->regions[$name] = $content;
        return $this;
    }

    #region implements INodable

    /** @inheritDoc */
    public function toNodes(Engine $engine, HTMLDocument $document): Generator
    {
        $children = $this->childrenExplicit
            ? $this->children
            : ($this->props[AbstractComponent::PROP_CHILDREN] ?? null);
        $rawRegions = $this->props[AbstractComponent::PROP_REGIONS] ?? [];
        $propRegions = is_array($rawRegions) ? $rawRegions : [];
        $regions = array_merge($propRegions, $this->regions);
        $props = array_merge($this->props, [
            AbstractComponent::PROP_CHILDREN => $children,
            AbstractComponent::PROP_REGIONS  => $regions,
        ]);

        $component = $engine->getComponent($this->name, $document);
        foreach ($component->render($props) as $content) {
            yield from $engine->contentResolver->toNodes($document, $content);
        }
    }

    #endregion implements INodable
}
