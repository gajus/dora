<?php
$form = new \ay\thorax\Form();

$input = $form->input('foo', ['value' => 'bar']);

$input->setAttribute('value', 'baz');

echo $input;