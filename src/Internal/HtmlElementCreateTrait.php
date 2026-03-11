<?php

declare(strict_types=1);

namespace Manychois\Pompom\Internal;

use Dom\Element;

/**
 * Trait for creating HTML elements with attributes and children.
 */
trait HtmlElementCreateTrait
{
    /**
     * Creates an `<a>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function a(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('a', $attributes, $children);
    }

    /**
     * Creates an `<abbr>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function abbr(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('abbr', $attributes, $children);
    }

    /**
     * Creates an `<address>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function address(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('address', $attributes, $children);
    }

    /**
     * Creates an `<article>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function article(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('article', $attributes, $children);
    }

    /**
     * Creates a void `<area>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function area(string|array $attributes = []): Element
    {
        return $this->createElement('area', $attributes, null);
    }

    /**
     * Creates an `<aside>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function aside(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('aside', $attributes, $children);
    }

    /**
     * Creates an `<audio>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function audio(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('audio', $attributes, $children);
    }

    /**
     * Creates a `<b>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function b(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('b', $attributes, $children);
    }

    /**
     * Creates a void `<base>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function base(string|array $attributes = []): Element
    {
        return $this->createElement('base', $attributes, null);
    }

    /**
     * Creates a `<bdi>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function bdi(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('bdi', $attributes, $children);
    }

    /**
     * Creates a `<bdo>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function bdo(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('bdo', $attributes, $children);
    }

    /**
     * Creates a `<blockquote>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function blockquote(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('blockquote', $attributes, $children);
    }

    /**
     * Creates a `<body>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function body(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('body', $attributes, $children);
    }

    /**
     * Creates a void `<br>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function br(string|array $attributes = []): Element
    {
        return $this->createElement('br', $attributes, null);
    }

    /**
     * Creates a `<button>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function button(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('button', $attributes, $children);
    }

    /**
     * Creates a `<canvas>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function canvas(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('canvas', $attributes, $children);
    }

    /**
     * Creates a `<caption>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function caption(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('caption', $attributes, $children);
    }

    /**
     * Creates a `<cite>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function cite(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('cite', $attributes, $children);
    }

    /**
     * Creates a `<code>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function code(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('code', $attributes, $children);
    }

    /**
     * Creates a void `<col>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function col(string|array $attributes = []): Element
    {
        return $this->createElement('col', $attributes, null);
    }

    /**
     * Creates a `<colgroup>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function colgroup(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('colgroup', $attributes, $children);
    }

    /**
     * Creates a `<data>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function data(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('data', $attributes, $children);
    }

    /**
     * Creates a `<datalist>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function datalist(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('datalist', $attributes, $children);
    }

    /**
     * Creates a `<dd>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function dd(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('dd', $attributes, $children);
    }

    /**
     * Creates a `<del>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function del(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('del', $attributes, $children);
    }

    /**
     * Creates a `<details>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function details(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('details', $attributes, $children);
    }

    /**
     * Creates a `<dfn>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function dfn(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('dfn', $attributes, $children);
    }

    /**
     * Creates a `<dialog>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function dialog(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('dialog', $attributes, $children);
    }

    /**
     * Creates a `<div>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function div(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('div', $attributes, $children);
    }

    /**
     * Creates a `<dl>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function dl(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('dl', $attributes, $children);
    }

    /**
     * Creates a `<dt>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function dt(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('dt', $attributes, $children);
    }

    /**
     * Creates an `<em>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function em(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('em', $attributes, $children);
    }

    /**
     * Creates a void `<embed>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function embed(string|array $attributes = []): Element
    {
        return $this->createElement('embed', $attributes, null);
    }

    /**
     * Creates a `<fieldset>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function fieldset(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('fieldset', $attributes, $children);
    }

    /**
     * Creates a `<figcaption>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function figcaption(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('figcaption', $attributes, $children);
    }

    /**
     * Creates a `<figure>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function figure(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('figure', $attributes, $children);
    }

    /**
     * Creates a `<footer>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function footer(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('footer', $attributes, $children);
    }

    /**
     * Creates a `<form>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function form(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('form', $attributes, $children);
    }

    /**
     * Creates an `<h1>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function h1(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('h1', $attributes, $children);
    }

    /**
     * Creates an `<h2>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function h2(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('h2', $attributes, $children);
    }

    /**
     * Creates an `<h3>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function h3(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('h3', $attributes, $children);
    }

    /**
     * Creates an `<h4>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function h4(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('h4', $attributes, $children);
    }

    /**
     * Creates an `<h5>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function h5(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('h5', $attributes, $children);
    }

    /**
     * Creates an `<h6>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function h6(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('h6', $attributes, $children);
    }

    /**
     * Creates a `<head>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function head(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('head', $attributes, $children);
    }

    /**
     * Creates a `<header>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function header(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('header', $attributes, $children);
    }

    /**
     * Creates a void `<hr>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function hr(string|array $attributes = []): Element
    {
        return $this->createElement('hr', $attributes, null);
    }

    /**
     * Creates an `<html>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function html(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('html', $attributes, $children);
    }

    /**
     * Creates an `<i>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function i(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('i', $attributes, $children);
    }

    /**
     * Creates an `<iframe>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function iframe(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('iframe', $attributes, $children);
    }

    /**
     * Creates a void `<img>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function img(string|array $attributes = []): Element
    {
        return $this->createElement('img', $attributes, null);
    }

    /**
     * Creates a void `<input>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function input(string|array $attributes = []): Element
    {
        return $this->createElement('input', $attributes, null);
    }

    /**
     * Creates an `<ins>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function ins(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('ins', $attributes, $children);
    }

    /**
     * Creates a `<kbd>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function kbd(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('kbd', $attributes, $children);
    }

    /**
     * Creates a `<label>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function label(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('label', $attributes, $children);
    }

    /**
     * Creates a `<legend>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function legend(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('legend', $attributes, $children);
    }

    /**
     * Creates a void `<link>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function link(string|array $attributes = []): Element
    {
        return $this->createElement('link', $attributes, null);
    }

    /**
     * Creates an `<li>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function li(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('li', $attributes, $children);
    }

    /**
     * Creates a `<main>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function main(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('main', $attributes, $children);
    }

    /**
     * Creates a `<map>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function map(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('map', $attributes, $children);
    }

    /**
     * Creates a `<mark>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function mark(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('mark', $attributes, $children);
    }

    /**
     * Creates a `<menu>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function menu(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('menu', $attributes, $children);
    }

    /**
     * Creates a void `<meta>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function meta(string|array $attributes = []): Element
    {
        return $this->createElement('meta', $attributes, null);
    }

    /**
     * Creates a `<meter>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function meter(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('meter', $attributes, $children);
    }

    /**
     * Creates a `<nav>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function nav(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('nav', $attributes, $children);
    }

    /**
     * Creates a `<noscript>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function noscript(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('noscript', $attributes, $children);
    }

    /**
     * Creates an `<object>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function object(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('object', $attributes, $children);
    }

    /**
     * Creates an `<ol>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function ol(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('ol', $attributes, $children);
    }

    /**
     * Creates an `<optgroup>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function optgroup(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('optgroup', $attributes, $children);
    }

    /**
     * Creates an `<option>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function option(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('option', $attributes, $children);
    }

    /**
     * Creates an `<output>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function output(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('output', $attributes, $children);
    }

    /**
     * Creates a `<p>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function p(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('p', $attributes, $children);
    }

    /**
     * Creates a void `<param>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function param(string|array $attributes = []): Element
    {
        return $this->createElement('param', $attributes, null);
    }

    /**
     * Creates a `<picture>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function picture(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('picture', $attributes, $children);
    }

    /**
     * Creates a `<pre>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function pre(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('pre', $attributes, $children);
    }

    /**
     * Creates a `<progress>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function progress(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('progress', $attributes, $children);
    }

    /**
     * Creates a `<q>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function q(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('q', $attributes, $children);
    }

    /**
     * Creates an `<rp>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function rp(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('rp', $attributes, $children);
    }

    /**
     * Creates an `<rt>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function rt(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('rt', $attributes, $children);
    }

    /**
     * Creates a `<ruby>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function ruby(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('ruby', $attributes, $children);
    }

    /**
     * Creates a `<s>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function s(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('s', $attributes, $children);
    }

    /**
     * Creates a `<samp>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function samp(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('samp', $attributes, $children);
    }

    /**
     * Creates a `<script>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function script(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('script', $attributes, $children);
    }

    /**
     * Creates a `<section>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function section(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('section', $attributes, $children);
    }

    /**
     * Creates a `<select>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function select(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('select', $attributes, $children);
    }

    /**
     * Creates a `<small>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function small(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('small', $attributes, $children);
    }

    /**
     * Creates a void `<source>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function source(string|array $attributes = []): Element
    {
        return $this->createElement('source', $attributes, null);
    }

    /**
     * Creates a `<span>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function span(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('span', $attributes, $children);
    }

    /**
     * Creates a `<strong>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function strong(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('strong', $attributes, $children);
    }

    /**
     * Creates a `<style>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function style(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('style', $attributes, $children);
    }

    /**
     * Creates a `<sub>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function sub(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('sub', $attributes, $children);
    }

    /**
     * Creates a `<summary>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function summary(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('summary', $attributes, $children);
    }

    /**
     * Creates a `<sup>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function sup(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('sup', $attributes, $children);
    }

    /**
     * Creates a `<table>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function table(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('table', $attributes, $children);
    }

    /**
     * Creates a `<tbody>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function tbody(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('tbody', $attributes, $children);
    }

    /**
     * Creates a `<td>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function td(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('td', $attributes, $children);
    }

    /**
     * Creates a `<textarea>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function textarea(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('textarea', $attributes, $children);
    }

    /**
     * Creates a `<tfoot>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function tfoot(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('tfoot', $attributes, $children);
    }

    /**
     * Creates a `<th>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function th(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('th', $attributes, $children);
    }

    /**
     * Creates a `<thead>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function thead(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('thead', $attributes, $children);
    }

    /**
     * Creates a `<time>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function time(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('time', $attributes, $children);
    }

    /**
     * Creates a `<title>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function title(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('title', $attributes, $children);
    }

    /**
     * Creates a void `<track>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function track(string|array $attributes = []): Element
    {
        return $this->createElement('track', $attributes, null);
    }

    /**
     * Creates a `<tr>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function tr(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('tr', $attributes, $children);
    }

    /**
     * Creates a `<ul>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function ul(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('ul', $attributes, $children);
    }

    /**
     * Creates a `<video>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     * @param mixed                       $children   Child nodes of the element.
     *
     * @return Element The created element.
     */
    public function video(string|array $attributes = [], mixed $children = null): Element
    {
        return $this->createElement('video', $attributes, $children);
    }

    /**
     * Creates a void `<wbr>` element.
     *
     * @param string|array<string, mixed> $attributes Attributes of the element in key-value pairs.
     *                                                If a string is given, it is treated as the class attribute.
     *                                                If value is null, the attribute is removed.
     *
     * @return Element The created element.
     */
    public function wbr(string|array $attributes = []): Element
    {
        return $this->createElement('wbr', $attributes, null);
    }
}
