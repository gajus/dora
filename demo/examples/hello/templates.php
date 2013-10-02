<?php
$form = new \ay\thorax\Form();

// Use labels to build form.
$label = new \ay\thorax\Label($form);

// This is using the default template (see /ay/thorax/label.php).
echo $label->input('test');

// Here is how you build your own template.
$label = new \ay\thorax\Label($form, function ($input) {
	return '
	<div class="thorax-row custom">
		<label for="' . $input->getId() . '">' . $input->getLabel() . '</label>
		' . $input . '
	</div>';
});

echo $label->input('test', null, ['label' => 'Test Label']);