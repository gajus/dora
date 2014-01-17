<?php
// First Form parameter is used to populate default Form data.
// Second Form parameter identifies input source (default: $_POST).
$form = new \gajus\dora\Form([
	'foo' => 'Heeeere\'s...Johnny!',
	'bar' => 'Yada, yada, yada.',
	'baz' => 0,
]);

// First Input parameter is input name. Other parameters are optional.
// Casting Input object into a string will trigger __toString.
echo $form->input('foo');

// Second parameter defines input attributes.
echo $form->input('bar', ['type' => 'textarea', 'class' => 'test']);

// Third parameter defines input properties.
echo $form->input('baz', null, ['options' => ['Knock, knock...', 'Come in.']]);


#$input = new \gajus\dora\Input('test')