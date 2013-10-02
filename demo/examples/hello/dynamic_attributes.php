<?php
$form = new \ay\thorax\Form($data = [
	'user' => ['password_test' => 'test'] // Password [value] will be intentionally omitted.
]);

// "input" method returns instance of \ay\thorax\form\Input.
$input = $form->input('user[password_test]', ['type' => 'password']);

// Retrieving input[id] when one is not defined will generate a random [id].
$input->getId();

// Default input label is derived from input[name].
?>
<p><?=$input->getLabel()?></p>
<?php
// casting \ay\thorax\form\Input to string will trigger __toString method.
echo $input;