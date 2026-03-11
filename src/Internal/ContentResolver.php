<?php

declare(strict_types=1);

namespace Manychois\Pompom\Internal;

use Closure;
use Dom\Element;
use Dom\HTMLDocument;
use Dom\Node;
use Generator;
use InvalidArgumentException;
use Manychois\Pompom\ContentResolverInterface as IContentResolver;
use Manychois\Pompom\Engine;
use Manychois\Pompom\NodableInterface;
use TypeError;

/**
 * Converts mixed data to Dom\Node using the document passed to toNodes().
 *
 * Use toNodes($document, $content) to turn strings, scalars, existing nodes,
 * or iterables into nodes suitable for appending to the document.
 */
class ContentResolver implements IContentResolver
{
    /**
     * Creates a new content resolver.
     *
     * @param Engine $engine Engine used to resolve NodableInterface content.
     */
    public function __construct(private readonly Engine $engine)
    {
    }

    #region implements IContentResolver

    /** @inheritDoc */
    public function changeAttributes(Element $element, mixed $name, mixed $value): void
    {
        if (!is_string($name)) {
            $msg = sprintf('Attribute name must be a string, got %s.', get_debug_type($name));
            throw new InvalidArgumentException($msg);
        }

        if ($value === null) {
            $element->removeAttribute($name);
            return;
        }

        if ($name === 'class' || strtolower($name) === 'classname') {
            if (is_string($value) || is_array($value)) {
                /** @var string|array<bool|string> $value */
                $this->setClassName($element, $value);
            } else {
                $msg = sprintf('Class attribute must be string or array, got %s.', get_debug_type($value));
                throw new InvalidArgumentException($msg);
            }
            return;
        }

        if (is_scalar($value)) {
            $element->setAttribute($name, (string) $value);
        } else {
            $msg = sprintf('Cannot convert %s to an attribute value.', get_debug_type($value));
            throw new InvalidArgumentException($msg);
        }
    }

    /** @inheritDoc */
    public function changeClasslist(Element $element, string|array $classname): void
    {
        if (is_string($classname)) {
            $tokens = self::cleanTokens($classname);
            $element->classList->add(...$tokens);
            return;
        }

        foreach ($classname as $key => $value) {
            if (is_int($key)) {
                if (is_string($value)) {
                    $tokens = self::cleanTokens($value);
                    $element->classList->add(...$tokens);
                } else {
                    throw new TypeError('Cannot convert boolean to a class name.');
                }
            } else {
                $tokens = self::cleanTokens($key);
                if ($value === true) {
                    $element->classList->add(...$tokens);
                } else {
                    $element->classList->remove(...$tokens);
                }
            }
        }
    }

    /** @inheritDoc */
    public function setClassName(Element $element, string|array $classname): void
    {
        if (is_string($classname)) {
            if ($classname === '') {
                $element->removeAttribute('class');
            } else {
                $element->setAttribute('class', $classname);
            }
        } else {
            $element->removeAttribute('class');
            $this->changeClasslist($element, $classname);
        }
    }

    /** @inheritDoc */
    public function toNodes(HTMLDocument $document, mixed $content): Generator
    {
        if ($content === null || $content === '') {
            // Do nothing.
        } elseif ($content instanceof Node) {
            yield $content;
        } elseif (is_scalar($content)) {
            yield $document->createTextNode((string) $content);
        } elseif (is_iterable($content)) {
            foreach ($content as $item) {
                yield from $this->toNodes($document, $item);
            }
        } elseif ($content instanceof NodableInterface) {
            yield from $content->toNodes($this->engine, $document);
        } elseif ($content instanceof Closure) {
            $result = $content->__invoke();
            yield from $this->toNodes($document, $result);
        } else {
            throw new TypeError(sprintf('Cannot convert %s to nodes.', get_debug_type($content)));
        }
    }

    #endregion implements IContentResolver

    /**
     * Cleans up a class name string by splitting it into tokens and filtering out empty tokens.
     *
     * @param string $s Class name string to clean.
     *
     * @return array<int, string> Cleaned class name tokens.
     */
    private static function cleanTokens(string $s): array
    {
        $tokens = preg_split('/\s+/', $s);
        assert(is_array($tokens));
        return array_filter($tokens, static fn (string $t): bool => $t !== '');
    }
}
