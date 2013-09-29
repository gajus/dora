<?php
namespace ay\thorax;

class Form {
	private
		$data = [],
		$input,
		$input_index = [];
	
	public function __construct (array $data = null) {
		$this->data = $data === null ? $_POST : $data;
	}
	
	public function input ($name, array $attributes = null, array $parameters = null) {
		$this->input_index[$name] = isset($this->input_index[$name]) ? $this->input_index[$name] + 1 : 0;
	
		$input = new form\Input($this, $name, $attributes, $parameters, $this->input_index[$name]);
	
		$this->input[] = $input;
		
		return $input;
	}
	
	public function getData () {
		return $this->data;
	}
}