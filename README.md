# Pompom

Pompom is a modern PHP library from **manychois** for building HTML templates with a **component-based, DOM-first** approach. It uses PHP 8.5+ and the `Dom\*` namespaced APIs (not legacy `DOMDocument`) so you can construct reusable, testable HTML in pure PHP—no string concatenation or separate templating syntax.

## Requirements

- **PHP** >= 8.5  
- **ext-dom** (enabled)

## Installation

```bash
composer require manychois/pompom
```

## How it works

1. You register a **component resolver** (e.g. `Psr4ComponentResolver`) that maps names like `"hello-page"` to component classes.
2. The **engine** creates an empty `Dom\HTMLDocument` and resolves the root component by name.
3. The engine calls **`render($props)`** on the component. Components receive **props only in `render()`**, not via the container; the container is used only for constructor dependencies (`document`, `engine`).
4. Each component **yields** output (nodes, scalars, or nested component references). The engine converts each item to `Dom\Node` via a **content resolver** and appends them to the document.
5. The **root component is responsible for the full document structure** (`<html>`, `<head>`, `<body>`). The engine does not add them.
6. You get back a **`Dom\HTMLDocument`** and output it with `saveHtml()`, `saveHtmlFile()`, etc.

## Quick example

```php
use Manychois\Pompom\Engine;
use Manychois\Pompom\Internal\Psr4ComponentResolver;

$resolver = new Psr4ComponentResolver([
    'MyApp\Components' => __DIR__ . '/src/Components',
]);

$engine = new Engine($resolver);
$document = $engine->render('hello-page', ['name' => 'World']);

echo $document->saveHtml();
```

Components extend `AbstractComponent`, receive `HTMLDocument $document` and `Engine $engine` in the constructor, and implement **`render(array $props = []): Generator`**. Use the injected **`NodeUtility`** (e.g. `$this->nodeUtility->createElement(...)`) to build nodes, and **`component($name, $props)`** plus **`withChildren()`** / **`withRegion()`** to compose other components and slots. See [AGENTS.md](AGENTS.md) for full architecture and contracts.

## Features

- **DOM-first**: Build HTML with `Dom\Document`, `Dom\Element`, `Dom\HTMLDocument`; output via `saveHtml()` / `saveHtmlFile()`.
- **Composable components**: Reference other components by name; pass props and slot content (children, named regions).
- **Mixed output**: Components yield nodes, strings, or component references; the content resolver turns everything into nodes.
- **Testable**: Assert on the DOM tree or serialized HTML in PHPUnit.
- **Optional Prettier**: Format the document for readable HTML before output.

## Development

```bash
composer install
composer test      # PHPUnit
composer analyse   # PHPStan (max level, strict rules)
composer lint      # PHP_CodeSniffer (docblocks, 120-char lines)
```

Implementation details, conventions, and code knowledge are in **[AGENTS.md](AGENTS.md)**.

## License

MIT. See [LICENSE](LICENSE).
