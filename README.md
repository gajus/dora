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

## Documentation & Examples

[Interactive documentation](https://dev.anuary.com/3d48b41b-9949-56cd-b062-40b729e53521/demo/) covers all of the Dora functionality.

## The purpose of the UID

If input is genered using an instance of `Form`, then each input is generated together with a hidden input `gajus[dora][uid]`, e.g. rendering text input will generate:

```html
<input id="dora-input-130102852" name="user[first_name]" type="text" value="test">
<input type="hidden" name="gajus[dora][uid]" value="2953768934">
```

The purpose of the UID value is to identify the form that was submitted. You can use `isSubmitted` method to capture specific form. Furthermore, UID is used to carry the flash data from page to page and to populate only the physical form that was submitted, as opposed to all the instances of inputs re-appearing with the same input name as the data in the flash container.

## Note

Dora assumes that application is designed using [Post/Redirect/Get](http://en.wikipedia.org/wiki/Post/Redirect/Get) pattern. Dora will not populate form upon POST request because it is assumed that POST request will result in a redirect. However, Dora will copy POST data and store it in a temporary session. This is achieved using `./src/inc/agent.php` script. If you are using composer, then this script is automatically included in every request.
