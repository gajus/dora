<?php
namespace ay\thorax\label;

class Template {
	private
		$form,
		$input,
		$label,
		$label_parameters = [];
	
	public function __construct (\ay\thorax\Form $form, \ay\thorax\Label $label, \ay\thorax\form\Input $input) {
		$this->form = $form;
		$this->input = $input;
		$this->label = $label;
	}
	
	public function getInput () {
		return $this->input;
	}
	
	public function stringify () {
		$template = $this->label->getTemplate();
		
		return $template($this->input, $this->form);
	}
	
	public function __toString () {
		return $this->stringify();
	}
}