<?php
$form = new \gajus\dora\Form();

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