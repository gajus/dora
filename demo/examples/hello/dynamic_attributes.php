<?php
$form = new \ay\thorax\Form();

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
} catch (\ErrorException $e) {
	?>
	<div class="thorax-message"><?=$e->getMessage()?></div>
	<?php
}