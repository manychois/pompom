# Pompom

**Pompom** is a PHP library for building HTML with a **component-based, DOM-first** workflow: you compose templates from PHP classes that yield `Dom\*` nodes, not from string assembly or a separate template language.

---

## Get started

You will install the package and render one named component to a `Dom\HTMLDocument`, then print HTML. You need PHP 8.5+ with `ext-dom`.

### 1. Install

```bash
composer require manychois/pompom
```

### 2. Render a root component

Register a **component resolver** (here `Psr4ComponentResolver`) so a string name maps to a component class. Build an **engine**, call **`render`**, then serialize:

```php
use Manychois\Pompom\Engine;
use Manychois\Pompom\Internal\Psr4ComponentResolver;

$resolver = new Psr4ComponentResolver([
    'MyApp\\Components' => __DIR__ . '/src/Components',
]);

$engine = new Engine($resolver);
$document = $engine->render('hello-page', ['name' => 'World']);

echo $document->saveHtml();
```

You should see HTML produced by your `hello-page` component (that class must exist at the path implied by your PSR-4 mapping).

---

## How to …

### Run tests and quality checks in this repository

If `documentation/internal/` is missing, initialize the submodule:

```bash
git submodule update --init documentation/internal
```

Then:

```bash
composer install
composer test    # PHPUnit (with coverage)
composer phpstan # static analysis
composer phpcs   # style and docblocks
```

---

## About Pompom

Pompom is built around **PHP 8.5+** and the modern **`Dom\*` API** (`Dom\HTMLDocument`, `Dom\Element`, …), not the legacy `DOMDocument` stack. The goal is reusable, testable pieces that return real DOM trees.

**Resolution and rendering.** You map arbitrary component names (e.g. `hello-page`) to classes via a **component resolver**. The **engine** creates an **empty** `Dom\HTMLDocument`, instantiates the root component, and iterates **`render($props)`**. Constructor injection gives only shared dependencies such as the document and engine; **render-time data is always `$props`**, not the DI container.

**What components output.** Components **yield** mixed values (text, nodes, nested component references). A **content resolver** turns each chunk into `Dom\Node` instances and the engine appends them. The **root component** is responsible for the full document shape (`<html>`, `<head>`, `<body>` if you need a full page); the engine does not insert those for you.

**Composition.** Typical pieces include **`AbstractComponent`**, **`NodeUtility`** for element helpers, **`component()`** / **`ComponentBuilder`** for child components, and **children** / **named regions** for slot-like content.

**Why DOM-first.** Output stays structured and easy to assert in tests (`saveHtml()`, walking nodes) and avoids ad-hoc concatenation; optional **Prettier** can indent HTML for readability before serialization.

---

## Reference

| | |
| --- | --- |
| **Package** | `manychois/pompom` |
| **PHP** | `>= 8.5` |
| **Extensions** | `ext-dom` |

| Symbol | Role |
| --- | --- |
| `Engine` | Builds an empty `Dom\HTMLDocument`, resolves the root component, consumes `render()` output. |
| `ComponentResolverInterface` | Maps a component name to a component class (e.g. `Psr4ComponentResolver`). |
| `AbstractComponent` | Base for components; `render()` / `getContent()` pipeline, `component()`, children and regions. |
| `ContentResolverInterface` | Turns mixed yielded content into `Dom\Node` for a given document. |
| `Prettier` | Optional in-place formatting before `saveHtml()`. |

Serialization uses `Dom\HTMLDocument` methods such as `saveHtml()` and `saveHtmlFile()`.
