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

```html+php
$form = new \Gajus\Dora\Form([
    'foo' => 'Heeeere\'s...Johnny!',
    'bar' => 'Yada, yada, yada.',
    'baz' => 0,
]);

// \gajus\dora\Form::input($name[, array $attributes = []][, array $properties = []])

// Casting Input object into a string will trigger __toString.
echo $form->input('foo');
echo $form->input('bar', ['type' => 'textarea', 'class' => 'test']);
echo $form->input('baz', null, ['options' => ['Knock, knock...', 'Come in.']]);
```

### Input Name

```html+php
$form = new \Gajus\Dora\Form();

// "input" method returns instance of gajus\dora\form\Input.
// Use "name" property to give Input a human-readable name.
$input = $form->input('foo', null, ['name' => 'Foo Label Property']);
?>
<div class="dora-input">
    <label><?=$input->getProperty('name')?></label>
    <?=$input?>
</div>
<?php
// When "name" property is not provided, Dora will derive name from the input "name" attribute.
$input = $form->input('bar[baz_qux][]');
?>
<div class="dora-input">
    <label><?=$input->getProperty('name')?></label>
    <?=$input?>
</div>
```

### Attributes

```html+php
$form = new \Gajus\Dora\Form();

$input = $form->input('foo', ['value' => 'bar']);

// You can manipulate attributes after Input object has been created.
// @todo Oops! You cannot. Allowing to change attributes post initialisation allows unpredicted behaviour when it comes to value resolution. "setAttribute" has been made private method.

/*$input->setAttribute('value', 'baz');

echo $input;

// You can get attribute values before/after input has been stringified.
?>
<dl>
    <dt>Name</dt>
    <dd><?=$input->getAttribute('name')?></dd>
</dl>
<?php
// But you cannot change attribute value after input has been stringified.

try {
    $input->setAttribute('value', 'qux');
} catch (\Exception $e) {
    ?>
    <div class="dora-message"><?=$e->getMessage()?></div>
    <?php
}*/

// Requesting [id] attribute when one does not exist will force a new (random) id.
$foo = $form->input('foo');
?>
<dl>
    <dt>Id</dt>
    <dd><?=$foo->getAttribute('id')?></dd>
</dl>
<?php

$bar = $form->input('bar');

echo $bar;

// This will not work if you request a dynamic attribute after input has been stringified.
try {
    $bar->getAttribute('id');
} catch (\LogicException $e) {
    ?>
    <div class="dora-message"><?=$e->getMessage()?></div>
    <?php
}
```

### Value Resolution

```html+php
<?php
$form = new \Gajus\Dora\Form([
    'mu' => ['lt' => ['id' => ['im' => ['en' => ['si' => ['on' => ['al' => 'Multidimensional Data']]]]]]],
    'array' => ['', 'second'],
    'checkbox_multiple' => ['c', 'd'],
    'radio' => 'b'
]);

// Multidimensional data
echo $form->input('mu[lt][id][im][en][si][on][al]');
?>
<hr>
<?php
// Array data
echo $form->input('array[]');
echo $form->input('array[]');
echo $form->input('array[]');
?>
<hr>
<?php
// Passing "options" parameter implies that the input type is "select".
echo $form->input('select_multiple[]', ['multiple' => 'multiple'],
    ['options' => ['Quick!', 'To the Batmobile!', 'Da-na-na-na', 'Na-na-na-na'] ]);
?>
<hr>
<?php
echo $form->input('checkbox_multiple[]', ['type' => 'checkbox', 'value' => 'a']);
echo $form->input('checkbox_multiple[]', ['type' => 'checkbox', 'value' => 'b']);
echo $form->input('checkbox_multiple[]', ['type' => 'checkbox', 'value' => 'c']);
echo $form->input('checkbox_multiple[]', ['type' => 'checkbox', 'value' => 'd']);
?>
<hr>
<?php
echo $form->input('radio', ['type' => 'radio', 'value' => 'a']);
echo $form->input('radio', ['type' => 'radio', 'value' => 'b']);
```

### Template

```html+php
<?php
namespace demo;

$form = new \Gajus\Dora\Form();

// Using default template (dress):

$dress = new \Gajus\Dora\Dress($form);

echo $dress->input('foo');
echo $dress->input('bar');

// Define custom template:

class MyDress extends \Gajus\Dora\Dress\Manikin {
    public function toString () {
        $input = $this->getInput();

        ob_start();
        ?>
        <div class="dora-input custom">
            <label for="<?=$input->getAttribute('id')?>"><?=$input->getProperty('name')?></label>
            <?=$input?>
        </div>
        <?php
        return ob_get_clean();
    }
}

$my_dress = new \Gajus\Dora\Dress($form, 'demo\MyDress');

echo $my_dress->input('baz', null, ['name' => 'Baz Custom Name']);
echo $my_dress->input('qux', null, ['name' => 'Qux Custom Name']);
```

## The purpose of the UID

If input is genered using an instance of `Form`, then each input is generated together with a hidden input `gajus[dora][uid]`, e.g. rendering text input will generate:

```html
<input id="dora-input-130102852" name="user[first_name]" type="text" value="test">
<input type="hidden" name="gajus[dora][uid]" value="2953768934">
```

The purpose of the UID value is to identify the form that was submitted. You can use `isSubmitted` method to capture specific form. Furthermore, UID is used to carry the flash data from page to page and to populate only the physical form that was submitted, as opposed to all the instances of inputs re-appearing with the same input name as the data in the flash container.

## Note

Dora assumes that application is designed using [Post/Redirect/Get](http://en.wikipedia.org/wiki/Post/Redirect/Get) pattern. Dora will not populate form upon POST request because it is assumed that POST request will result in a redirect. However, Dora will copy POST data and store it in a temporary session. This is achieved using `./src/inc/agent.php` script. If you are using composer, then this script is automatically included in every request.
