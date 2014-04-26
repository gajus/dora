<?php
$form = new \Gajus\Dora\Form();

// Attibutes are defined using the second Input parameter.

echo $form->input('foo', ['data-foo' => 'bar']);

// If Input "id" attribute is not defined, and it is requested before Input is stringified,
// then a new random (semi-persistent) ID will be generated.

$input = $form->input('foo');
?>
<dl>
    <dt>Id</dt>
    <dd><?=$input->getAttribute('id')?></dd>
</dl>