<?php
$form = new \ay\thorax\Form();

if (isset($_POST['action']['submit'])) {
	ay('test');
}

$label = $form->addLabel();
?>
<form action="" method="post">
	<?=$label->input('first_name', null, ['label' => 'Custom Label'])?>
	<?=$form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit'])?>
</form>