<?php
// \gajus\dora\Form(array $default_data = [][, array $input = $_POST])

// $default_data is used to populate form when $input is empty.
$form = new \gajus\dora\Form([
	'foo' => 'Heeeere\'s...Johnny!',
	'bar' => 'Yada, yada, yada.',
	'baz' => 0,
]);

// \gajus\dora\Form::input($name[, array $attributes = []][, array $properties = []])

// Casting Input object into a string will trigger __toString.
echo $form->input('foo');
echo $form->input('bar', ['type' => 'textarea', 'class' => 'test']);
echo $form->input('baz', null, ['options' => ['Knock, knock...', 'Come in.']]);