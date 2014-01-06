<?php
namespace ay\thorax\rule;

class Is_Eq_A extends \ay\thorax\User_Rule {
	private
		$input,
		$message = '{thorax.label} must be eq to \"a\".';
		
	public function __construct (\ay\thorax\form\Input $input) {
		$this->input = $input;
	}

	public function isValid () {
		return $this->input->getValue() === 'a';
	}
	
	public function getMessage () {
		return $this->message;
	}
	
	public function getInput () {
		return $this->input;
	}
}