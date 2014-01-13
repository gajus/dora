<?php
$form = new \gajus\thorax\Form();

// Label = Template

// You can use default label.
$label = $form->addLabel();

echo $label->input('foo');
echo $label->input('bar');

// To define custom template, you need a function/closure that accepts \gajus\thorax\form\Input instance.
$template = function (\gajus\thorax\form\Input $input) {
	ob_start();
	?>
	<div class="thorax-input custom">
		<label for="<?=$input->getAttribute('id')?>"><?=$input->getProperty('label')?></label>
		<?=$input?>
	</div>
	<?php
	return ob_get_clean();
};

// Then add/refer to that function/closure when adding a new Label instance.
$custom_label = $form->addLabel($template);

echo $custom_label->input('baz', null, ['label' => 'Baz Custom Label']);
echo $custom_label->input('qux', null, ['label' => 'Qux Custom Label']);