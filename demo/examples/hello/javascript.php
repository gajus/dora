<?php
// Thorax is using V8 Javascript Engine to process the validation rules.
// You can export the same rules and use them to perform client-side validation.

$form = new \ay\thorax\Form();

$label = $form->addLabel();

$rule = $form->addRule('test');

$rule->add(['foo'])
?>
<form action="" method="post">
	<?=$label->input('foo')?>
	<?=$label->input('bar')?>
	
	<?=$form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit'])?>
	
	<script>
	<?=$form->exportRules()?>
	</script>
</form>