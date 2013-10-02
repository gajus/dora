<?php
$form = new \ay\thorax\Form();

// Input method returns instance of form\Input.
// Use "label" property to give Input a human-readable name.
$foo = $form->input('foo', null, ['label' => 'Foo Label Property']);

?>
<div class="thorax-row">
	<label><?=$foo->getProperty('label')?></label>
	<?php
	// Casting Input object into string will trigger __toString.
	echo $foo;
	?>
</div>
<?php

// If label hasn't got label property, Thorax will derive label from the input name.
$bar = $form->input('bar[baz_qux][]');
?>
<div class="thorax-row">
	<label><?=$bar->getProperty('label')?></label>
	<?=$bar?>
</div>