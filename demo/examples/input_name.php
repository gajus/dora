<?php
$form = new \Gajus\Dora\Form([], null);

// "input" method returns an instance of Gajus\Dora\Input.
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
<dl>
    <dt>bar[baz_qux][]</dt>
    <dd><?=$input->getProperty('name')?></dd>
</dl>