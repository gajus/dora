# Dora

[![Build Status](https://travis-ci.org/gajus/dora.png?branch=master)](https://travis-ci.org/gajus/dora)
[![Coverage Status](https://coveralls.io/repos/gajus/dora/badge.png)](https://coveralls.io/r/gajus/dora)

Input generation library.

Dora has evolved from a [single function](https://gist.github.com/gajus/8392582). Generating input does not require much more than a single function. However, procedural implementation encouraged bad practises, e.g. relying on the state of `$GLOBALS`. Dora embodies the simplicity of the original implementation with a light touch of OO to properly handle environment variables.

Dora does not in any way interfere with form styling. However, Dora comes with a Dress. Dress is used to suround input with additional markup (e.g. label element, input description placeholder). Default dress is Dora.

## Using Dora

```php
<?php
$form = new \Gajus\Dora\Form(['foo' => 2]);

echo $form->input('foo', ['data-custom-attribute' => 'test'], ['options' => [1 => 'Knock, knock...', 2 => 'Come in.']]);
```

The above will produce:

```html
<select data-custom-attribute="test" name="foo" type="select">
	<option value="1">Knock, knock...</option>
	<option value="2" selected="selected">Come in.</option>
</select>
```

### Form & input

Dora does not provide a method to generate `<form>`. The `Form` method in Dora represnts form as the container of the data. Input generated using an instance of the `Form` will resolve the instance data.

```html+php
// Populate Form with data.
$form = new \Gajus\Dora\Form([
    'foo' => 'Heeeere\'s...Johnny!',
    'bar' => 'Yada, yada, yada.',
    'baz' => 0,
]);
```

In the above example, input `foo` generated using an instance of the `Form` will inherit "Heeeere's...Johnny!" value, e.g.

```php
echo $form->input('foo');
echo $form->input('bar', ['type' => 'textarea', 'class' => 'test']);
echo $form->input('baz', null, ['options' => ['Knock, knock...', 'Come in.']]);
```

