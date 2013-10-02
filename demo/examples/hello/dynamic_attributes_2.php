<?php
$form = new \ay\thorax\Form();

$input = $form->input('test');

ob_start();

echo $input;

ob_clean();

try {
	$input->getAttribute('id');
} catch (\ErrorException $e) {
	?>
	<p>Retrieving random <code>input[id]</code> is not possible after the input has been displayed.</p>
	<?php
}

// The same rule applies to setting input attributes after input has been displayed.