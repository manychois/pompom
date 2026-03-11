# Pompom Project Knowledge Base

## Project identity

- **Project**: Pompom
- **Type**: PHP library
- **Company**: manychois
- **Primary developer**: Siu Pang Tommy Choi (`manychois@manychois.com.au`)

## Purpose and vision

- **Goal**: Provide a modern, DOM-first way to build HTML templates in PHP.
- **Approach**: Wrap and extend PHP's modern `Dom\Document` and related `Dom\*` APIs (not legacy `DOMDocument`) to construct reusable, testable HTML components in pure PHP.
- **Non-goals (for now)**:
  - Implementing a full template language.
  - Mixing business logic into templates.

## Technical requirements

- **PHP version**: >= 8.5 (hard requirement).
- **Extensions**:
  - `ext-dom` (required).
- **Package name**: `manychois/pompom`.
- **License**: MIT.

## Namespaces and structure

- **Root namespace**: `Manychois\Pompom\` → `src/`
- **Test namespace**: `Manychois\Pompom\Tests\` → `tests/`
- **Current structure**:
  - `src/` – library source (DOM helpers and components will live here).
  - `tests/` – PHPUnit test cases.

## Tooling and commands

- **Dependency manager**: Composer
  - Install dependencies: `composer install`
- **Testing**: PHPUnit
  - Config: `phpunit.xml.dist`
  - Run tests: `composer test`
- **Static analysis**: PHPStan (level max, strict rules)
  - Config: `phpstan.neon.dist`
  - Run: `composer analyse`
- **Lint (docblocks + line length)**: PHP_CodeSniffer
  - Config: `phpcs.xml.dist` (Squiz class/function comments, 120-char line limit)
  - Run: `composer lint`
- **Auto-fix lint**: PHPCBF
  - Run: `composer fix`

## Design notes (initial)

- Prefer **strongly-typed** PHP 8.5+ APIs and strict types.
- Expose APIs that work directly with DOM nodes/trees rather than plain strings.
- Keep public API small and focused; internal helpers can be more flexible.
- When a class implements an interface, wrap the implementing methods in a `#region implements <InterfaceName>` … `#endregion` block (e.g. `#region implements ComponentResolverInterface`).
- Order class methods by: **static** then **instance**; within each, by **visibility** (public, protected, private); within each visibility group, **alphabetically** by method name (put `__construct` first among public instance methods).
- Always use the latest `Dom\*` namespaced classes (for example `Dom\Document`) instead of the older global classes like `DOMDocument`.
- For HTML pages, the engine should construct and return a `Dom\HTMLDocument`, and callers are expected to use its `saveHtml()`, `saveHtmlFile()`, `saveXml()`, or `saveXmlFile()` methods for output.
- `Dom\Document` is reserved for generic/internal utilities; if a generic document must be output as HTML, convert or rebuild it into a `Dom\HTMLDocument` first rather than relying on missing methods.

## Methodology: component-based DOM templates

- Treat the **entire HTML template** as a tree of **composable components**.
- A **root component** (you may call it a view, template, or page component) is invoked by name, along with **properties/props** that customise its output.
- Each component:
  - Receives input data via the `$props` argument to `render($props)` (not via the container).
  - May use other components via `Internal\ComponentBuilder` (e.g. `component($name, $props)`) and slots via `placeChildren()` / `placeRegion()`.
  - Yields mixed output (nodes, scalars, or component references); the engine converts each item to `Dom\Node` via `ContentResolver` and appends them to the document.
- The render pipeline:
  1. The engine creates a **completely empty** `Dom\HTMLDocument` (no `<html>`, `<head>`, or `<body>`).
  2. User selects a root component by name and passes properties.
  3. The root component is responsible for the full document structure: it must yield nodes that represent `<html>`, `<head>`, and `<body>` (or equivalent). The engine attaches those nodes to the document.
  4. The library returns the populated `Dom\HTMLDocument` ready for output (string, stream, response, etc.).
- Every HTML output from Pompom should come from this **component composition over DOM** process, not from ad-hoc string concatenation.

### Notes on `Dom\HTMLDocument` vs `Dom\Document`

- `Dom\HTMLDocument` in PHP adds convenience factory and serialization methods that are not available on `Dom\Document`, including:
  - `createEmpty(string $encoding = 'UTF-8'): Dom\HTMLDocument`
  - `createFromFile(string $path, int $options = 0, ?string $overrideEncoding = null): Dom\HTMLDocument`
  - `createFromString(string $source, int $options = 0, ?string $overrideEncoding = null): Dom\HTMLDocument`
  - `saveHtml(?Dom\Node $node = null): string`
  - `saveHtmlFile(string $filename): int|false`
  - `saveXml(?Dom\Node $node = null, int $options = 0): string|false`
  - `saveXmlFile(string $filename, int $options = 0): int|false`
- Pompom should lean on these methods by returning `Dom\HTMLDocument` from its main render entry points, so consumers never have to manually serialize from a bare `Dom\Document`.

## Code knowledge (architecture and contracts)

- **Engine**
  - Creates an empty `Dom\HTMLDocument`, resolves the root component by name via `ComponentResolverInterface`, instantiates it via the container with only `document` and `engine` (no render-time props in the container), then iterates `component->render($props)` and appends nodes.
  - Converts each yielded item from `render()` to nodes using `ContentResolverInterface::toNodes($document, $item)` and appends those nodes to the document.
  - Exposes `contentResolver` (default `ContentResolver`) and `getComponent($name, $document)` for resolving component references (e.g. from `Internal\ComponentBuilder`).

- **Container and instantiation**
  - The PSR-11–compatible container is used only for **constructor** dependencies (`document`, `engine`). Render-time data is **not** passed into `container->make()`; it is passed as the `$props` argument to `render($props)`.

- **AbstractComponent**
  - Constructor receives `HTMLDocument $document` and `Engine $engine`. A `NodeUtility` is created from the document and the engine’s `contentResolver` (for mixed→Node in createElement children).
  - `render(array $props = []): Generator` yields **mixed** values; the engine passes each through `contentResolver->toNodes()` and appends the resulting nodes.
  - Provides `component($name, $props)` returning an `Internal\ComponentBuilder` (for composing other components), and `placeChildren($props)` / `placeRegion($name, $props)` to read content set by a parent via `Internal\ComponentBuilder::withChildren()` / `withRegion()`.
  - Constants `PROP_CHILDREN` and `PROP_REGIONS` are the keys used in `$props` for children and named regions.

- **Internal\ComponentBuilder**
  - Represents a reference to a component by **name** and default **props**. Supports `withChildren(mixed)` and `withRegion(string $name, mixed)` for slots.
  - Implements `NodableInterface`; `toNodes(Engine, HTMLDocument)` merges children/regions into props, gets the component via `engine->getComponent($name, $document)`, runs `render($props)`, and yields nodes via the engine’s content resolver.

- **ContentResolver / ContentResolverInterface**
  - Converts **mixed** content to zero or more `Dom\Node` for a given document. Handles: `null`, scalars (text node), `Dom\Node` (yielded; import if needed), iterables (recursive), `NodableInterface` (e.g. `Internal\ComponentBuilder`), and `Closure` (invoke then resolve). Other types throw `TypeError`.

- **Component resolution**
  - `ComponentResolverInterface` maps a string identifier (e.g. `"hello-page"`) to a `class-string<AbstractComponent>`. `Psr4ComponentResolver` is the PSR-4–based implementation (kebab-case name → class).

- **NodeUtility**
  - Optional helper for components: `createElement()`, `createText()`, `createDoctype()`. Children in `createElement()` are resolved via `ContentResolverInterface` (mixed → Node). Constructor: `(HTMLDocument $document, ContentResolverInterface $contents)`.

- **NodableInterface**
  - Objects that can be turned into nodes in a given document: `toNodes(Engine $engine, HTMLDocument $document): Generator`. Used by `ContentResolver` and by `Internal\ComponentBuilder`.

- **Prettier (HTML indentation)**
  - Inserts whitespace so `saveHtml()` output is indented. **Four positions** (each insert or not): **0** before-start (before opening tag, inserted by parent), **1** after-start (after open tag, before first child), **2** before-end (after last child, before closing tag), **3** after-end (after closing tag, inserted by parent).
  - **Convention**: `o` = insert newline+indent, `x` = do not insert. Pattern arrays are named by the 4-char pattern (e.g. `$oxxo` = insert at 0 and 3 only). **All 15 pattern arrays** are defined (ooox, ooxo, ooxx, oxoo, oxox, oxxo, oxxx, xooo, xoox, xoxo, xoxx, xxoo, xxox, xxxo, xxxx); there is no `$oooo` array—elements not in any array get default `[true,true,true,true]` (insert at all positions). `$xxxx` = no indent (inline elements). Extenders may override any array.
  - `getPattern(string $localName): array{0:bool, 1:bool, 2:bool, 3:bool}` returns the four flags; default when not in any array is `[true, true, true, true]`.
  - `format(HTMLDocument $document): void` formats in place. Protected helpers: `indentBeforeStart()`, `indentAfterStart()`, `indentBeforeEnd()`, `indentAfterEnd()` (each takes document, element, depth). `$indent` (default `'  '`) is the indent string.

- **Engine constructor and DI**
  - `__construct(ComponentResolverInterface $components, ?ContentResolverInterface $contentResolver = null, ?IContainer $container = null)`. Uses PHP-DI `ContainerBuilder`; if `$container` is provided it is wrapped. Component is instantiated via `$container->make($componentClass, ['document' => $document, 'engine' => $this])`.

- **ComponentResolverInterface**
  - `has(string $name): bool`, `resolve(string $name): class-string<AbstractComponent>` (throws `InvalidArgumentException` when name cannot be resolved).

- **Psr4ComponentResolver**
  - Constructor: `array $namespaces` (base namespace => base directory path). Identifier is treated as kebab-case (e.g. `hello-page` → `HelloPage`); first matching namespace whose directory contains the corresponding PHP file is used. Caches resolved class names.

## Dev / evaluation

- **`dev/`** is git-ignored. It holds a separate Composer project for evaluating the library (namespace `Manychois\PompomEval`, autoload from `dev/src/`). Entry point `dev/index.php` uses the Engine to render a root component (e.g. HelloPage) and output HTML. Components and helpers (e.g. TailwindBase) live under `dev/src/Components/`. All evaluator work should go under `dev/`.

## Documentation strategy

- High-level overview and install instructions live in `README.md`.
- Implementation details, conventions, and evolving requirements live in this `AGENTS.md`.
- When requirements change, update this file first, then align code/tests.

## Open questions / future decisions

- Exact shape of public DOM helper APIs.
- How to structure components (class-based, function-based, or hybrids).
- Integration patterns with common PHP frameworks (if any) or remain framework-agnostic.

