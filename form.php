<?php
namespace ay\thorax;

class Form {
	private
		$data = [],
		$input_index = [];
	
	public function __construct (array $data = null) {
		$this->data = $data === null ? [] : $data;
		
		// Prevent accidental output buferring.
		ob_start();
	}
	
	public function input ($name, array $attributes = null, array $parameters = null) {
		if (!isset($this->input_index[$name])) {
			$this->input_index[$name] = [];
		}
		
		$input = new form\Input($this, $name, $attributes, $parameters, count($this->input_index[$name]));
		
		$this->input_index[$name][] = $input;
		
		return $input;
	}
	
	public function submit ($event) {
		if (!isset($_POST['thorax']['submit'])) {
			return;
		}
		
		$input_lookup = [];
		$input_submit_index = [];
		
		foreach ($this->input_index as $input) {
			// input[type="create"] have unique names, thus $input[0].
			if ($input[0]->getAttribute('type') === 'submit') {
				$input_lookup[] = $input[0]->getAttribute('thorax_uid');
				
				$input_submit_index[$input[0]->getAttribute('name')] = $input[0];
			}
		}
		
		if (!in_array($_POST['thorax']['submit'], $input_lookup)) {
			return;
		}
		
		$flatten = function ($input, $parent = []) use (&$flatten) {
			$return = [];
			
			foreach ($input as $k => $v) {
				if (is_array($v)) {
					$return = array_merge($return, $flatten($v, array_merge($parent, [$k])));
				} else {
					if ($parent) {
						$key = implode('][', $parent) . '][' . $k . ']';
						
						if (substr_count($key, ']') != substr_count($key, '[')) {
							$key = preg_replace('/\]/', '', $key, 1);
						}
					} else {
						$key = $k;
					}			
					
					$return[$key] = $v;
				}
			}
			
			return $return;
		};
		
		$match = array_intersect_key($this->input_index, $flatten($_POST), $input_submit_index);
		
		if ($match) { // @todo check if the name is right
			return current($match)[0];
		}
	}
	
	public function getData () {
		return $this->data;
	}
}