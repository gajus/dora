<?php
$form = new \ay\thorax\Form();

// It is a looks a lot less messy without all the comments.
$form->addRule('is_eq_a', ['first_name']);

$label = $form->addLabel();
?>
<form action="#example-hello__validation_2" method="post">
	<?=$label->input('first_name', null, ['label' => 'Custom Label'])?>
	<?=$form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit'])?>
</form>
<?php
if ($form->isSubmitted()) {
	// Shorthand method to pass errors to the Input.
	$form->getErrors(true);
	
	header('Location: ' . $_SERVER['REQUEST_URI']);
}