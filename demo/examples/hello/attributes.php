<?php
$form = new \ay\thorax\Form();

$input = $form->input('foo', ['value' => 'bar']);

// You can manipulate attributes after Input object has been created.
$input->setAttribute('value', 'baz');

echo $input;

// You can get attribute values before/after input has been stringified.
?>
<dl>
	<dt>name</dt>
	<dd><?=$input->getAttribute('name')?></dd>
</dl>
<?php
// But you cannot change attribute value after input has been stringified.

try {
	$input->setAttribute('value', 'qux');
} catch (\ErrorException $e) {
	?>
	<div class="thorax-message"><?=$e->getMessage()?></div>
	<?php
}