<?php
namespace ay\thorax;

class Form {
	private
		$input;
	
	public function __construct () {
	
	}
	
	public function input ($name) {
		$input = new form\Input($this, $name);
	
		$this->input[] = $input;
		
		return $input;
	}
}