<?php
namespace ay\thorax;

class Error implements \Serializable {
	private
		$rule,
		$input,
		$response;

	public function __construct (rules\Rule $rule, form\Input $input, array $response) {
		$this->rule = $rule;
		$this->input = $input;
		$this->response = $response;
	}
	
	public function getRule () {
		return $this->rule;
	}
	
	public function getInput () {
		return $this->input;
	}
	
	public function getMessage () {
		return $this->response['message'];
	}
	
	public function serialize () {
		return json_encode($this->response);
	}
	
	public function unserialize ($data) {
		$this->response = json_decode($data, true);
	}
}