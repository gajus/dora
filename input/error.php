<?php
namespace ay\thorax\input;

class Error {
	private
		$input,
		$response;

	public function __construct (\ay\thorax\form\Input $input, array $response) {
		$this->input = $input;
		$this->response = $response;
	}
	
	public function getInput () {
		return $this->input;
	}
	
	public function getMessage () {
		return $this->response['message'];
	}
}