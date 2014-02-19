<?php
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