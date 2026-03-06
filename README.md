# Pompom

Pompom is a modern PHP library from **manychois** for building HTML templates using the latest PHP DOM features.

It provides a thin, expressive layer over PHP's `DOMDocument` and related APIs so you can construct reusable, testable HTML components in pure PHP without mixing in string concatenation or templating languages.

> Status: early project scaffolding. The public API is not yet stable.

## Requirements

- **PHP**: >= 8.5
- **Extension**: `ext-dom` enabled

## Installation

Use Composer to install the library:

```bash
composer require manychois/pompom
```

## Basic idea (planned)

The library will focus on:

- **DOM-first templating**: Build HTML using native DOM types instead of strings.
- **Composable components**: Small reusable helpers for common layout and form patterns.
- **Testability**: DOM-based assertions in PHPUnit for validating generated markup.

Concrete examples and API documentation will be added once the first release is ready.

## Development

Clone the repository and install dependencies:

```bash
composer install
```

Run the test suite:

```bash
composer test
```

## License

Pompom is open-sourced software licensed under the **MIT license**.
