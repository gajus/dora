<?php
namespace gajus\dora;

class Label {
	private
		$form,
		$template;
	
	public function __construct (Form $form, \Closure $template = null) {
		$this->form = $form;
		
		if ($template === null) {
			$template = 'gajus\dora\Label::defaultTemplate';
		}
		
		if (!is_callable($template)) {
			throw new \InvalidArgumentException('Invalid template.');
		}
		
		$this->template = $template;
	}
	
	public function input ($name, array $attributes = null, array $properties = null) {
		$input = $this->form->input($name, $attributes, $properties);
		
		return new label\Template($this->form, $this, $input);
	}
	
	public function getTemplate () {
		return $this->template;
	}

	static private function defaultTemplate (form\Input $input, Form $form) {
		$errors = [];

		ob_start();?>
		<div class="dora-input">
			<label for="<?=$input->getAttribute('id')?>"><?=$input->getProperty('name')?></label>
			<?=$input?>
			<?php if ($errors):?>
			<ul class="dora-error">
				<li><?=implode('</li><li>', $errors)?></li>
			</ul>
			<?php endif;?>
		</div>
		<?php return ob_get_clean();
	}
}