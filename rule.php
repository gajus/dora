<?php
namespace ay\thorax;

class Rule {
	private
		$form,
		$rule,
		$index = [];

	public function __construct (Form $form, $path = 'is_eq_a') {
		$this->form = $form;
		
		$this->load($path);
	}
	
	public function load ($requested_path) {
		if (strpos($requested_path, '/') !== 0) {
			$path = __DIR__ . '/rules/library/' . $requested_path;
		}
		
		if (strpos(strrev($path), 'sj.') === false && file_exists($path . '.js')) {
			$path .= '.js';
		}
		
		if (!file_exists($path)) {
			throw new \ErrorException('Rule "' . $requested_path . '" not found.');
		}
	
		$filename = pathinfo($path)['filename'];
			
		$this->rule = new rules\Rule($filename, file_get_contents($path));
	}
	
	/**
	 * @param string $input_name Begining input_name with bachslash will assume it is a regular expression.
	 */
	public function add ($input_name) {
		if (is_array($input_name)) {
			foreach ($input_name as $in) {
				$this->add($in);
			}
			
			return;
		}
	
		$this->index[] = $input_name;
		
		$this->index = array_unique($this->index);
	}
	
	//public function getInvalidInput () {
	public function getErrors () {
		$index = $this->form->getInputIndex();
		
		ay('#', $index);
		
		/*
		
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
		}*/
		
		return $failed;
	}
}