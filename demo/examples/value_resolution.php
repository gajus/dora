<?php
$form = new \Gajus\Dora\Form([
    'mu' => ['lt' => ['id' => ['im' => ['en' => ['si' => ['on' => ['al' => 'Multidimensional Data']]]]]]],
    'array' => ['', 'second'],
    'checkbox_multiple' => ['c', 'd'],
    'radio' => 'b'
]);

// Multidimensional data
echo $form->input('mu[lt][id][im][en][si][on][al]');

// Array data
echo $form->input('array[]');
echo $form->input('array[]');
echo $form->input('array[]');

// Passing "options" parameter implies that the input type is "select".
echo $form->input('select_multiple[]', ['multiple' => 'multiple'],
    ['options' => ['Quick!', 'To the Batmobile!', 'Da-na-na-na', 'Na-na-na-na'] ]);

echo $form->input('checkbox_multiple[]', ['type' => 'checkbox', 'value' => 'a']);
echo $form->input('checkbox_multiple[]', ['type' => 'checkbox', 'value' => 'b']);
echo $form->input('checkbox_multiple[]', ['type' => 'checkbox', 'value' => 'c']);
echo $form->input('checkbox_multiple[]', ['type' => 'checkbox', 'value' => 'd']);

echo $form->input('radio', ['type' => 'radio', 'value' => 'a']);
echo $form->input('radio', ['type' => 'radio', 'value' => 'b']);