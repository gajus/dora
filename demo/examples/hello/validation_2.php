<?php
$form = new \ay\thorax\Form();

$rule = $form->addRule('is_eq_a', 'first_name');

ay($rule->getName(), $rule->getFunction());

exit;

$label = $form->addLabel();
?>
<form action="" method="post">
	<?=$label->input('first_name', null, ['label' => 'Custom Label'])?>
	<?=$form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit'])?>
</form>