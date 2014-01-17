<?php
$form = new \gajus\dora\Form();

// "input" method returns instance of \gajus\dora\Form\Input.
// Use "name" property to give Input a human-readable name.
$foo = $form->input('foo', null, ['name' => 'Foo Label Property']);
?>
<div class="dora-row">
	<label><?=$foo->getProperty('name')?></label>
	<?=$foo?>
</div>
<?php
// If Input instance hasn't got "name" property, Thorax will derive name from the input name attribute.
$input = $form->input('bar[baz_qux][]');
?>
<div class="dora-row">
	<label><?=$input->getProperty('name')?></label>
	<?=$input?>
</div>