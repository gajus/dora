<?php
namespace ay\thorax;

class Label {
	private
		$form,
		$template;
	
	/**
	 * @param closure $template
	 */
	public function __construct (Form $form, $template = null) {
		$this->form = $form;
		
		if ($template === null) {
			$template = 'ay\thorax\label_default_template';
		}
		
		if (!is_callable($template)) {
			throw new \ErrorException('Invalid template format.');
		}
		
		$this->template = $template;
	}
	
	public function input ($name, array $attributes = null, array $properties = null) {
		$input = $this->form->input($name, $attributes, $properties);
		
		return new label\Template($this->form, $this, $input);
	}
	
	function getTemplate () {
		return $this->template;
	}
}

function label_default_template (form\Input $input, Form $form) {
	$inbox = $input->getInbox();
	
	$errors = [];
	
	if ($inbox) {
		foreach ($inbox as $i) {
			if ($i instanceof \ay\thorax\input\Error) {
				$errors[] = $i->getMessage();
			}
		}
	}
	
	$rules = array_map(function ($e) { return 'thorax-rule-' . $e->getName(); }, $input->getRules());
	
	ob_start();?>
	<div class="thorax-row <?=implode(' ', $rules)?>">
		<label for="<?=$input->getAttribute('id')?>"><?=$input->getProperty('label')?></label>
		<?=$input?>
		<?php if ($errors):?>
		<ul class="thorax-error">
			<li><?=implode('</li><li>', $errors)?></li>
		</ul>
		<?php endif;?>
	</div>
	<?php return ob_get_clean();
};