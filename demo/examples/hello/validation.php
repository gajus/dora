<?php
$form = new \ay\thorax\Form();

$label = $form->addLabel(function ($input) {
	ob_start();?>
	<div class="thorax-row">
		<label for="<?=$input->getAttribute('id')?>"><?=$input->getLabel()?></label>
		<?=$input?>
		<?php if ($input->getInbox()):?>
		<pre><?=implode(' ', $input->getInbox())?></pre>
		<?php endif;?>
	</div>
	<?php
	return ob_get_clean();
});

$rule = $form->addRule('is_eq_a');

// Attach rule to input[name="first_name"].
$rule->add('first_name');
// You can also pass an array.
$rule->add(['first_name', 'last_name']);
// Name begining with a "/" (backslash) will be interpreted as regular-expression.
$rule->add('/([a-z]+)_name/');
// Dupliacte matches will be removed.
?>
<form action="" method="post">
	<?=$label->input('first_name')?>
	<?=$label->input('last_name')?>
	
	<?=$form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit'])?>
</form>
<?php
if ($form->isSubmitted()) {
	// getErrors method ought to be used only after the form has been generated.
	// This is done to access properties of the elements in the property,
	// e.g. input[name="first_name"] has a custome label property, "Custom Label".
	if ($errors = $form->getErrors()) {
		foreach ($errors as $error) {
			$error->getInput()->pushInbox($error);
		}
	}
}