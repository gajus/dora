<?php
// This example demonstrates how to generate basic input.
// Values passed the Form constructor are mapped to the generated Input.

// This example is using the default input template.
// Therefore, input comes wraped in <div class="dora-input"></div> container
// with dynamically generated input name property.

/**
 * @param array $data Data used to populate Input generated using an instance of this Form.
 * @param null|string $template Template class name.
 */
$form = new \Gajus\Dora\Form([
    'foo' => 'Heeeere\'s...Johnny!',
    'bar' => 'Yada, yada, yada.',
    'baz' => 0,
]);

/**
 * Create Input associated with the Form instance data.
 * 
 * @param string $name
 * @param array $attributes
 * @param array $properties
 * @param string $template
 * @return \Gajus\Dora\Input
 */
echo $form->input('foo');
echo $form->input('bar', ['type' => 'textarea', 'class' => 'test']);
echo $form->input('baz', null, ['options' => ['Knock, knock...', 'Come in.']]);