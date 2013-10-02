<?php
namespace ay\thorax\input;

class Error {
	private
		$input,
		$message;

	public function __construct (\ay\thorax\form\Input $input, $message) {
		$this->input = $input;
		$this->message = $message;
	}
	
	public function getInput () {
		return $this->input;
	}
	
	public function getMessage () {
		return $this->message;
	}
}