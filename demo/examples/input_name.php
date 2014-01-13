<?php
$form = new \gajus\thorax\Form();

// "input" method returns instance of \gajus\thorax\Form\Input.
// Use "name" property to give Input a human-readable name.
$foo = $form->input('foo', null, ['name' => 'Foo Label Property']);
?>
<div class="thorax-row">
	<label><?=$foo->getProperty('name')?></label>
	<?=$foo?>
</div>
<?php
// If Input instance hasn't got "name" property, Thorax will derive name from the input name attribute.
$input = $form->input('bar[baz_qux][]');
?>
<div class="thorax-row">
	<label><?=$input->getProperty('name')?></label>
	<?=$input?>
</div>