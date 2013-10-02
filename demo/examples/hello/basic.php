<?php
// First Form parameter is form data. If omitted, it will default to $_POST. 
$form = new \ay\thorax\Form($data = [
	'a' => 'Heeeere\'s...Johnny!',
	'b' => 'Yada, yada, yada.',
	'c' => 0,
]);

// The first parameter is input name.
// Second parameter defines input attributes.
echo $form->input('a', ['class' => 'test']);

// Use "type" attribute to change the input type.
echo $form->input('b', ['type' => 'textarea']);

// Third input parameter is for defining label properties, e.g.
// "options" in case of select, or "label" to change input label.
echo $form->input('c', ['type' => 'select'], ['options' => ['Knock, knock...', 'Come in.']]);