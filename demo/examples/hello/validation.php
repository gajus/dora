<?php
$form = new \ay\thorax\Form();

$label = $form->addLabel();

// Rules have to be applied before the form is generated.
// This allows Label to access Rule properties.

// Predefined rule (for testing purposes) to check if value is equal to "a".
$rule = $form->addRule('is_eq_a');

// Rules are attached using input name.
$rule->add(['first_name']);
// Name begining with a "/" (backslash) will be interpreted as a regular-expression.
$rule->add('/^last_([a-z]+)$/');

// Default Thorax label template will add "thorax-rule-{name}" class
// for every rule to the matched elements (look at the HTML output).
?>
<form action="#example-hello__validation" method="post">
	<?=$label->input('first_name')?>
	<?=$label->input('last_name')?>
	
	<?=$form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit'])?>
</form>
<?php
if ($form->isSubmitted()) {
	// getErrors need to access input properties (advisable).
	// Therefore, getErrors should be called only after the form has been generated.
	if ($errors = $form->getErrors()) {
		foreach ($errors as $error) {
			$error->getInput()->pushInbox($error);
		}
	}
	
	header('Location: ' . $_SERVER['REQUEST_URI']);
}