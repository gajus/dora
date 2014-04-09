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
    'qux' => ['1', '2']
]);

echo $form->input('foo');
echo $form->input('bar', ['type' => 'textarea', 'class' => 'test']);
echo $form->input('baz', null, ['options' => ['Knock, knock...', 'Come in.']]);
echo $form->input('qux[]');
echo $form->input('qux[]');
```

In the above example, `Input` with name "foo" will inherit "Heeeere's...Johnny!" value:

```html
<input name="foo" type="text" value="Heeeere's...Johnny!">
<textarea class="test" name="bar">Yada, yada, yada.</textarea>
<select name="baz">
    <option value="0" selected="selected">Knock, knock...</option>
    <option value="1">Come in.</option>
</select>
<input name="qux[]" type="text" value="1">
<input name="qux[]" type="text" value="2">
```

## Input

Input is a standalone entity defined with three parameters.

```php
/**
 * @param string $name Input name.
 * @param array $attributes HTML attributes.
 * @param array $properties Input properties, e.g. input name.
 */
new \Gajus\Dora\Input('foo', ['type' => 'textarea'], ['name' => 'Foo']);
```

Most of the time, `Form` will act as a factory to produce `Input` (like in all the examples on this page).

### Input name

The name of the control, which is submitted with the form data.

### HTML attributes

HTML attributes that are added to the generated input. All attributes will be taken literally except "type". "type" attribute will change the actual input type, e.g. "select" will make input `<select>`, "textarea" will make it `<textarea>`.

### Input Properties

Input properties are used at the time of generating the input template.

|name|description|
|---|---|
|`name`|Name is not a required property. Input `name` property is used when input is used in template, e.g. label. If input `name` property is not provided, it will be derived from the input HTML "name" attribute.|
|`options`|`options` property is not required. This proprety is for `<select>` input type. Passing this property will automatically assume that input type is "select".|

## Template

Most of the time you do not want naked `<input>`. `Input` can be dressed using templates. Dora comes with a inbuilt template called .. Dora.

The template itself has access to the `Input` instance, including all the properties that were passed at the time of creating the `Input`. Stripped down template logic is looking like this:

```html+php
<div class="dora-input<?=$input->getProperty('class')?>">
    <label for="<?=$input_id?>"><?=$input->getProperty('name')?></label>
    <div class="input">
        <?=$input->toString()?>
    </div>
    <?php if ($input->getProperty('name')):?>
    <div class="description">
        <p><?=$input->getProperty('name')?></p>
    </div>
    <?php endif;?>
</div>
```

### Writing a Template

Each template must extend `Gajus\Dora\Dress\Manikin`. Refer to the existing templates for examples. 

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