<?php
// First Form parameter is used to populate Form data.
$form = new \ay\thorax\Form([
	'foo' => 'Heeeere\'s...Johnny!',
	'bar' => 'Yada, yada, yada.',
	'baz' => 0,
]);

// First Input parameter is input name. Other parameters are optional.
echo $form->input('foo');

// Second Input parameter defines input attributes.
echo $form->input('bar', ['type' => 'textarea', 'class' => 'test']);

// Third input parameter defines input properties.
echo $form->input('baz', ['type' => 'select'], ['options' => ['Knock, knock...', 'Come in.']]);