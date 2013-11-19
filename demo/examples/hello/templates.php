<?php
$form = new \ay\thorax\Form();

$default_label = $form->addLabel();

echo $default_label->input('foo');

// Here is how you build your own template.
$custom_label = $form->addLabel(function (\ay\thorax\form\Input $input) {
	ob_start();
	?>
	<div class="thorax-input custom">
		<label for="<?=$input->getAttribute('id')?>"><?=$input->getProperty('label')?></label>
		<?=$input?>
	</div>
	<?php
	return ob_get_clean();
});

echo $custom_label->input('bar', null, ['label' => 'Bar Custom Label']);
echo $custom_label->input('baz', null, ['label' => 'Baz Custom Label']);