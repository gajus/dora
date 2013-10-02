<?php
namespace my\project;

// You can define a default template for your project.
function primary_label_template ($input) {
	ob_start();
	?>
	<div class="thorax-row custom">
		<label for="<?=$input->getAttribute('id')?>"><?=$input->getProperty('label')?></label>
		<?=$input?>
	</div>
	<?php
	return ob_get_clean();
};

$form = new \ay\thorax\Form();

$label = $form->addLabel('my\project\primary_label_template');

echo $label->input('bar');