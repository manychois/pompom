<?php

declare(strict_types=1);

namespace Manychois\Pompom;

use DI\Container;
use DI\ContainerBuilder;
use Dom\HTMLDocument;
use Manychois\Pompom\ContentResolverInterface as IContentResolver;
use Manychois\Pompom\Internal\ContentResolver;
use Psr\Container\ContainerInterface as IContainer;

/**
 * Renders components into DOM documents.
 */
class Engine
{
    private readonly Container $container;

    public readonly IContentResolver $contentResolver;

    /**
     * @param ComponentResolverInterface $components      Resolves component names to class names.
     * @param IContentResolver|null      $contentResolver Converts mixed render output to Dom\Node.
     *                                                    Defaults to ContentResolver($this).
     * @param IContainer|null            $container       Optional PSR-11 container to resolve dependencies.
     */
    public function __construct(
        private readonly ComponentResolverInterface $components,
        ?IContentResolver $contentResolver = null,
        ?IContainer $container = null,
    ) {
        $containerBuilder = new ContainerBuilder;
        if ($container !== null) {
            $containerBuilder->wrapContainer($container);
        }
        $this->container = $containerBuilder->build();
        $this->contentResolver = $contentResolver ?? new ContentResolver($this);
    }

    /**
     * Renders a component into a new DOM HTML document.
     *
     * The component name is an arbitrary identifier (e.g. "hello-page", "home"); the components
     * resolver maps it to a component class name. It does not have to be or resemble a class name.
     *
     * @param string               $componentName Component identifier (resolved to a class by the components resolver).
     * @param array<string, mixed> $props         Properties to pass to the component.
     *
     * @return HTMLDocument The rendered document.
     */
    public function render(string $componentName, array $props = []): HTMLDocument
    {
        $document = HTMLDocument::createEmpty();

        $component = $this->getComponent($componentName, $document);
        foreach ($component->render($props) as $node) {
            $document->appendChild($node);
        }

        return $document;
    }

    /**
     * Resolves a component name and returns an instance (for use by ComponentBuilder etc.).
     *
     * @param string       $componentName Component identifier (e.g. "hello-page").
     * @param HTMLDocument $document      Document to inject into the component.
     *
     * @return AbstractComponent The resolved component.
     */
    public function getComponent(string $componentName, HTMLDocument $document): AbstractComponent
    {
        return $this->instantiateComponent(
            $this->components->resolve($componentName),
            $document,
        );
    }

    /**
     * Instantiates a component via the container with the given document and this engine.
     *
     * @param string       $componentClass Component class name from the components resolver.
     * @param HTMLDocument $document       Document to inject into the component.
     *
     * @return AbstractComponent The instantiated component.
     *
     * @phpstan-param class-string<AbstractComponent> $componentClass
     */
    private function instantiateComponent(
        string $componentClass,
        HTMLDocument $document,
    ): AbstractComponent {
        $instance = $this->container->make($componentClass, [
            'document' => $document,
            'engine'   => $this,
        ]);
        assert($instance instanceof AbstractComponent);
        return $instance;
    }
}
