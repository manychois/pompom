<?php

declare(strict_types=1);

namespace Manychois\Pompom\Internal;

use InvalidArgumentException;
use Manychois\Pompom\AbstractComponent;
use Manychois\Pompom\ComponentResolverInterface as IComponentResolver;

/**
 * Resolves a component identifier to its full class name using PSR-4 namespace and directory mapping.
 *
 * This implementation treats the identifier as kebab-case with optional slashes (e.g. "abc-def/ghi-jkl")
 * and derives the relative class name "AbcDef\GhiJkl". The first matching base namespace whose directory
 * contains the corresponding PHP file is used. Other resolvers may map arbitrary names (e.g. "home") to classes.
 */
class Psr4ComponentResolver implements IComponentResolver
{
    /** @var array<string, class-string<AbstractComponent>|null> */
    private array $cache = [];

    /**
     * Creates a new PSR-4 component resolver.
     *
     * @param array<string, string> $namespaces Base namespace (key) to base directory path (value).
     */
    public function __construct(
        public readonly array $namespaces,
    ) {
    }

    #region implements IComponentResolver

    /** @inheritDoc */
    public function has(string $name): bool
    {
        return $this->find($name) !== null;
    }

    /** @inheritDoc */
    public function resolve(string $name): string
    {
        $class = $this->find($name);
        if ($class === null) {
            throw new InvalidArgumentException(sprintf('No component class found for name "%s".', $name));
        }
        return $class;
    }

    #endregion implements IComponentResolver

    /**
     * Builds the filesystem path for a class file under the given base directory.
     *
     * @param string $baseDir       Base directory path.
     * @param string $relativeClass Relative class name with backslashes.
     *
     * @return string Class file path.
     */
    private function classFilepath(string $baseDir, string $relativeClass): string
    {
        return rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
            . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
    }

    /**
     * Finds the full class name for the given component name if a matching file exists.
     *
     * @param string $name Component name to resolve.
     *
     * @return class-string<AbstractComponent>|null
     */
    private function find(string $name): ?string
    {
        if (array_key_exists($name, $this->cache)) {
            return $this->cache[$name];
        }

        $relativeClass = $this->nameToRelativeClass($name);

        foreach ($this->namespaces as $baseNamespace => $baseDir) {
            $path = $this->classFilepath($baseDir, $relativeClass);
            if (is_file($path)) {
                $ns = rtrim($baseNamespace, '\\');
                /** @var class-string<AbstractComponent> $class */
                $class = $ns . '\\' . $relativeClass;
                $this->cache[$name] = $class;
                return $class;
            }
        }

        $this->cache[$name] = null;
        return null;
    }

    /**
     * Converts kebab-case segment to PascalCase.
     *
     * @param string $segment Kebab-case segment.
     *
     * @return string Pascal-cased segment.
     */
    private function kebabToPascal(string $segment): string
    {
        $words = explode('-', $segment);
        $pascal = implode('', array_map(
            static fn (string $w) => ucfirst(strtolower($w)),
            array_filter($words, static fn (string $s) => $s !== ''),
        ));

        return $pascal !== '' ? $pascal : $segment;
    }

    /**
     * Converts component name to relative class name.
     *
     * @param string $name Component name (e.g. "abc-def/ghi-jkl").
     *
     * @return string Relative class name.
     */
    private function nameToRelativeClass(string $name): string
    {
        $parts = preg_split('~/+~', $name);
        assert(is_array($parts));
        $segments = array_filter($parts, static fn (string $s): bool => $s !== '');

        $segments = array_map(fn (string $segment): string => $this->kebabToPascal($segment), $segments);
        return implode('\\', $segments);
    }
}
