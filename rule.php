<?php
namespace ay\thorax;

class Rule {
	private
		$form,
		$rules = [],
		$rule,
		$map = [];

	public function __construct (Form $form) {
		$this->form = $form;
		
		$this->load('*.js');
		
		$this->rule = $this->rules['is_eq_a'];
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
	
	public function add ($input_name) { // Regex
		$this->map[] = $input_name;
		
		$this->map = array_unique($this->map);
	}
	
	public function getFailed () {
		$index = $this->form->getInputIndex();
		
		$failed = [];
		
		foreach ($this->map as $input_name) {
		
			$index = array_intersect_ukey($index, $index, function ($a, $b) use ($input_name) {
				return $a !== $input_name;
			});
		
			foreach ($index as $input) {
				if (!$this->rule->isValid($input[0])['passed']) {
					$failed[] = $input[0];
				}
			}
		}
		
		return $failed;
	}
}