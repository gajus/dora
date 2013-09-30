<?php
namespace ay\thorax;

class Form {
	private
		$data = [],
		$input_index = [];
	
	public function __construct (array $data = null) {
		$this->data = $data === null ? $_POST : $data;
	}
	
	public function input ($name, array $attributes = null, array $parameters = null) {
		if (!isset($this->input_index[$name])) {
			$this->input_index[$name] = [];
		}
		
		$input = new form\Input($this, $name, $attributes, $parameters, count($this->input_index[$name]));
		
		$this->input_index[$name][] = $input;
		
		return $input;
	}
	
	public function getData () {
		return $this->data;
	}
}