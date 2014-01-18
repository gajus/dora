<?php
#$form = new \gajus\dora\Form([
#	'baz' => 1,
#]);
#$form->input('baz', null, ['options' => ['Knock, knock...', 'Come in.']]);

/*$input = new \gajus\dora\Input('test');

$form = new \gajus\dora\Form([
	'bar' => 'Ok',
	'baz' => ['a', 'b']
]);*/

$form = new \gajus\dora\Form();
$dora = new \gajus\dora\Dress($form);

echo $dora->input('test');


#echo $form->input('bar');
#echo $form->input('bar');
#echo $form->input('baz[]');
#echo $form->input('bar[]test');

#$dress = new Dress($form);
#$dress->input('test');