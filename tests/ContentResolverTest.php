<?php

declare(strict_types=1);

namespace Manychois\PompomTests;

use Dom\HTMLDocument;
use InvalidArgumentException;
use Manychois\Pompom\Engine;
use Manychois\Pompom\Internal\ComponentBuilder;
use Manychois\Pompom\Internal\ContentResolver;
use Manychois\Pompom\Internal\Psr4ComponentResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;

/**
 * Tests for {@see ContentResolver}.
 */
final class ContentResolverTest extends TestCase
{
    private function psr4Engine(): Engine
    {
        $baseDir = __DIR__ . '/Fixtures/Psr4';

        return new Engine(new Psr4ComponentResolver([
            'Manychois\\PompomTests\\Fixtures\\Psr4\\' => $baseDir,
        ]));
    }

    #[Test]
    public function to_nodes_yields_nothing_for_null(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $nodes = iterator_to_array($resolver->toNodes($document, null), false);
        self::assertSame([], $nodes);
    }

    #[Test]
    public function to_nodes_yields_nothing_for_empty_string(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $nodes = iterator_to_array($resolver->toNodes($document, ''), false);
        self::assertSame([], $nodes);
    }

    #[Test]
    public function to_nodes_yields_text_node_for_scalar(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $nodes = iterator_to_array($resolver->toNodes($document, 42), false);
        self::assertCount(1, $nodes);
        self::assertSame('42', $nodes[0]->textContent);
    }

    #[Test]
    public function to_nodes_yields_flattened_nodes_for_iterable(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $nodes = iterator_to_array(
            $resolver->toNodes($document, ['a', ['b', 'c']]),
            false,
        );
        self::assertCount(3, $nodes);
        self::assertSame('a', $nodes[0]->textContent);
        self::assertSame('b', $nodes[1]->textContent);
        self::assertSame('c', $nodes[2]->textContent);
    }

    #[Test]
    public function to_nodes_yields_same_node_instance_in_document(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $span = $document->createElement('span');
        $nodes = iterator_to_array($resolver->toNodes($document, $span), false);
        self::assertSame($span, $nodes[0]);
    }

    #[Test]
    public function to_nodes_invokes_closure_and_resolves_result(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $nodes = iterator_to_array(
            $resolver->toNodes($document, static fn (): string => 'from-closure'),
            false,
        );
        self::assertSame('from-closure', $nodes[0]->textContent);
    }

    #[Test]
    public function to_nodes_resolves_nodable_via_engine(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $builder = new ComponentBuilder('hello-page', []);
        $nodes = iterator_to_array($resolver->toNodes($document, $builder), false);
        self::assertCount(1, $nodes);
        self::assertSame('hello-psr4', $nodes[0]->textContent);
    }

    #[Test]
    public function to_nodes_throws_type_error_for_unsupported_object(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Cannot convert stdClass to nodes.');
        iterator_to_array($resolver->toNodes($document, new stdClass()), false);
    }

    #[Test]
    public function change_attributes_throws_when_name_is_not_string(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $element = $document->createElement('div');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Attribute name must be a string');
        $resolver->changeAttributes($element, 1, 'x');
    }

    #[Test]
    public function change_attributes_removes_attribute_when_value_null(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $element = $document->createElement('div');
        $element->setAttribute('data-x', '1');
        $resolver->changeAttributes($element, 'data-x', null);
        self::assertFalse($element->hasAttribute('data-x'));
    }

    #[Test]
    public function change_attributes_sets_scalar_attribute(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $element = $document->createElement('div');
        $resolver->changeAttributes($element, 'id', 'main');
        self::assertSame('main', $element->getAttribute('id'));
    }

    #[Test]
    public function change_attributes_sets_class_from_string(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $element = $document->createElement('div');
        $resolver->changeAttributes($element, 'class', 'a b');
        self::assertSame('a b', $element->getAttribute('class'));
    }

    #[Test]
    public function change_attributes_throws_when_class_value_not_string_or_array(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $element = $document->createElement('div');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class attribute must be string or array');
        $resolver->changeAttributes($element, 'class', new stdClass());
    }

    #[Test]
    public function change_classlist_adds_tokens_from_string(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $element = $document->createElement('div');
        $resolver->changeClasslist($element, 'foo bar');
        self::assertTrue($element->classList->contains('foo'));
        self::assertTrue($element->classList->contains('bar'));
    }

    #[Test]
    public function change_classlist_adds_removes_via_associative_array(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $element = $document->createElement('div');
        $element->classList->add('keep', 'drop');
        $resolver->changeClasslist($element, [
            'add-me' => true,
            'drop'   => false,
        ]);
        self::assertTrue($element->classList->contains('keep'));
        self::assertTrue($element->classList->contains('add-me'));
        self::assertFalse($element->classList->contains('drop'));
    }

    #[Test]
    public function change_classlist_throws_type_error_for_boolean_token_in_list(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $element = $document->createElement('div');
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Cannot convert boolean to a class name.');
        $resolver->changeClasslist($element, [true]);
    }

    #[Test]
    public function set_class_name_removes_class_when_empty_string(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $element = $document->createElement('div');
        $element->setAttribute('class', 'x');
        $resolver->setClassName($element, '');
        self::assertFalse($element->hasAttribute('class'));
    }

    #[Test]
    public function set_class_name_rebuilds_from_array(): void
    {
        $document = HTMLDocument::createEmpty();
        $engine = $this->psr4Engine();
        $resolver = new ContentResolver($engine);
        $element = $document->createElement('div');
        $element->setAttribute('class', 'old');
        $resolver->setClassName($element, ['a' => true, 'old' => false]);
        self::assertTrue($element->classList->contains('a'));
        self::assertFalse($element->classList->contains('old'));
    }

}
