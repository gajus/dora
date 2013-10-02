<?php
$form = new \ay\thorax\Form();

$label = $form->addLabel();

$form->addRule('is_eq_a', ['foo']);
?>
<form action="" method="post">
	<?=$label->input('foo')?>
	
	<?=$form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit'])?>
</form>
<?php
if ($form->isSubmitted()) {
	$form->getErrors(true);
	
	header('Location: ' . $_SERVER['REQUEST_URI']);
}