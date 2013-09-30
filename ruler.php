<?php
namespace ay\thorax;

class Ruler {
	private
		$form,
		$rules = [];

	public function __construct (Form $form) {
		$this->form = $form;
		
		$this->load('*.js');
	}
	
	public function load ($path) {
		if (strpos($path, '/') !== 0) {
			$path = __DIR__ . '/ruler/library/' . $path;
		}
	
		foreach (glob($path) as $rule) {
			$filename = pathinfo($rule)['filename'];
			
			$this->rules[$filename] = new Ruler\Rule($filename, file_get_contents($rule));
		}
	}
	
	public function attach ($rule_name, $input_name) {
		if (!isset($this->rules[$rule_name])) {
			throw new \ErrorException('Unknown rule "' . $rule_name . '".');
		}
		
		$this->form->setRule($input_name, $this->rules[$rule_name]);
	}
	
	public function isValid () {
		ay( $this->form->data );
	}
}