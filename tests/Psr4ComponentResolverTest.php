<?php

declare(strict_types=1);

namespace Manychois\PompomTests;

use InvalidArgumentException;
use Manychois\Pompom\Internal\Psr4ComponentResolver;
use Manychois\PompomTests\Fixtures\Psr4\HelloPage;
use Manychois\PompomTests\Fixtures\Psr4\Nested\DeepPage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see Psr4ComponentResolver}.
 */
final class Psr4ComponentResolverTest extends TestCase
{
    private function resolver(): Psr4ComponentResolver
    {
        return new Psr4ComponentResolver([
            'Manychois\\PompomTests\\Fixtures\\Psr4\\' => __DIR__ . '/Fixtures/Psr4',
        ]);
    }

    #[Test]
    public function resolve_maps_kebab_name_to_class(): void
    {
        $resolver = $this->resolver();
        self::assertSame(HelloPage::class, $resolver->resolve('hello-page'));
    }

    #[Test]
    public function resolve_maps_slash_nested_kebab_path(): void
    {
        $resolver = $this->resolver();
        self::assertSame(DeepPage::class, $resolver->resolve('nested/deep-page'));
    }

    #[Test]
    public function has_returns_false_when_no_file_matches(): void
    {
        $resolver = $this->resolver();
        self::assertFalse($resolver->has('missing-component'));
    }

    #[Test]
    public function resolve_throws_invalid_argument_when_unknown(): void
    {
        $resolver = $this->resolver();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No component class found for name "nope".');
        $resolver->resolve('nope');
    }

    #[Test]
    public function resolve_caches_subsequent_lookups(): void
    {
        $resolver = $this->resolver();
        self::assertSame($resolver->resolve('hello-page'), $resolver->resolve('hello-page'));
    }

    #[Test]
    public function has_returns_false_after_negative_cache(): void
    {
        $resolver = $this->resolver();
        self::assertFalse($resolver->has('unknown-x'));
        self::assertFalse($resolver->has('unknown-x'));
    }
}
