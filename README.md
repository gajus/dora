# Dora

[![Build Status](https://travis-ci.org/gajus/dora.png?branch=master)](https://travis-ci.org/gajus/dora)
[![Coverage Status](https://coveralls.io/repos/gajus/dora/badge.png)](https://coveralls.io/r/gajus/dora)

Input generation library for value resolution, templating, CSRS and protection from XSS.

### CSRF

Input generated using Dora is accompanied with a hidden input, e.g. generating text input will produce:

```html
<input id="dora-input-130102852" name="user[first_name]" type="text" value="test">
<input type="hidden" name="gajus[dora][uid]" value="2953768934">
```

You can catch form submit with using the `Form` `isSubmitted` method, e.g.

```php+html
<?php
$form = new \Gajus\Dora\Form();

if ($form->isSubmitted()) {
    // This will be triggered if CSRF passed.
}
?>
<form action="" method="post">
    <?=$form->input('name')?>
    <?=$form->input('action[submit]', ['type' => 'submit'])?>
</form>
```

If you are not familiar with cross-site request forgery (CSRF, pronounced "sea-surf"), consider reading:

* http://shiflett.org/articles/cross-site-request-forgeries
* https://www.owasp.org/index.php/Cross-Site_Request_Forgery_%28CSRF%29

Input generation

Dora has evolved from a [single function](https://gist.github.com/gajus/8392582).



Generating input does not require much more than a single function. However, procedural implementation encourages bad practises, e.g. relying on the state of `$GLOBALS`. Dora embodies the simplicity of the original implementation with a light touch of OO to properly handle environment variables.

Dora does not in any way interfere with form styling. However, Dora comes with a Dress. Dress is used to suround input with additional markup (e.g. label element, input description placeholder). Default dress is Dora.

## Using Dora

```php
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

### Form

Dora does not provide a method to generate `<form>`. The `Form` method in Dora represnts form as the container of the data. Input generated using an instance of the `Form` will resolve the instance data.

```php
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

### Input

Input is any type of HTML input (select, textarea, etc.).
