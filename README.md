# Dora

[![Build Status](https://travis-ci.org/gajus/dora.png?branch=master)](https://travis-ci.org/gajus/dora)
[![Coverage Status](https://coveralls.io/repos/gajus/dora/badge.png)](https://coveralls.io/r/gajus/dora)

Input generation library for value resolution, templates, CSRS and protection from XSS.

## Form

Dora does not provide a method to generate `<form>`. `Form` is a data container. `Input` generated using an instance of the `Form` will inherit `Form` data.

```php
/**
 * @param array $data Data used to populate Input generated using an instance of this Form.
 */
$form = new \Gajus\Dora\Form([
    'foo' => 'Heeeere\'s...Johnny!',
    'bar' => 'Yada, yada, yada.',
    'baz' => 0,
]);

echo $form->input('foo');
echo $form->input('bar', ['type' => 'textarea', 'class' => 'test']);
echo $form->input('baz', null, ['options' => ['Knock, knock...', 'Come in.']]);
```

In the above example, `Input` with name "foo" generated using an instance of the `Form` will inherit "Heeeere's...Johnny!" value:

```html
<input name="foo" type="text" value="Heeeere's...Johnny!">
<textarea class="test" name="bar">Yada, yada, yada.</textarea>
<select name="baz">
    <option value="0" selected="selected">Knock, knock...</option>
    <option value="1">Come in.</option>
</select>
```

## Input

Input takes three parameters:

```php
/**
 * @param string $name Input name.
 * @param array $attributes HTML attributes.
 * @param array $properties Input properties, e.g. input name.
 */
new \Gajus\Dora\Input('foo', ['type' => 'textarea'], ['name' => 'Foo']);
```

### Input Properties

Input properties are used at the time of generating the input template.

|name|description|
|---|---|
|`name`|Name is not a required property. Input `name` property is used when input is used in template, e.g. label. If input `name` property is not provided, it will be derived from the input HTML "name" attribute.|
|`options`|`options` property is not required. This proprety is for `<select>` input type.|

## CSRF

Form generated using Dora need to be signed, e.g.

```php
$form = new \Gajus\Dora\Form();
?>
<form>
    <?=$form->sign()?>
    <input type="submit">
</form>
```

The generated signature consists of two tokes:

```html
<input type="hidden" name="gajus[dora][uid]" value="2953768934">
<input type="hidden" name="gajus[dora][csrf]" value="d0be2dc421be4fcd0172e5afceea3970e2f3d940">
```

* **UID** is used to recognise the an instance of the `Form` that has been used to generate the input. UID does not change between requests.
* **CSRF** is used to validate user session.

Use `isSubmitted` method to catch when the Form is submitted, e.g.

```php
// $form from the preceding example.

if ($form->isSubmitted()) {
    // This will be triggered if CSRF passed.
}
```

Do not use:

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {}
if (isset($_POST['gajus'])) {}
if (isset($_POST['your']['input'])) {}
```

The above example allows CSRF vulnerability.

If you are not familiar with cross-site request forgery (CSRF, pronounced "sea-surf"), read:

* http://shiflett.org/articles/cross-site-request-forgeries
* https://www.owasp.org/index.php/Cross-Site_Request_Forgery_%28CSRF%29

## Post/Redirect/Get

Dora assumes that application is designed using [Post/Redirect/Get](http://en.wikipedia.org/wiki/Post/Redirect/Get) pattern. Dora will not populate form upon POST request because it is assumed that POST request will result in a redirect. Dora will copy POST data and store it in a temporary session. This is achieved using [`./src/inc/agent.php`](src/inc/agent.php) script. If you are using [composer](https://getcomposer.org/), then this script is automatically included in every request.