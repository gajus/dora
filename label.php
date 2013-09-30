<?php
namespace ay\thorax;

class Label {
	private
		$form,
		$template;
	
	/**
	 * @param closure $template The first parameter passed to the template is Input instance.
	 */
	public function __construct (Form $form, $template = null) {
		$this->form = $form;
		
		if ($template === null) {
			$template = function ($input, $label) {
				return '
				<div class="thorax-row">
					<label for="' . $input->getId() . '">' . $input->getLabel() . '</label>
					' . $input . '
				</div>';
			};
		}
		
		if (!is_callable($template)) {
			throw new \ErrorException('Invalid template format.');
		}
		
		$this->template = $template;
	}
	
	public function input ($name, array $attributes = null, array $parameters = null) {
		$input = $this->form->input($name, $attributes, $parameters);
		
		return new label\Template($this->form, $this, $input);
	}
	
	function getTemplate () {
		return $this->template;
	}
}