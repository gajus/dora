<?php
namespace ay\thorax\input;

class Error /*implements \Serializable*/ {
	private
		#$rule,
		$input,
		$response;

	public function __construct (\ay\thorax\form\Input $input, array $response/*, \ay\thorax\rules\Rule $rule*/) {
		#$this->rule = $rule;
		$this->input = $input;
		$this->response = $response;
	}
	
	/*public function getRule () {
		return $this->rule;
	}*/
	
	public function getInput () {
		return $this->input;
	}
	
	public function getMessage () {
		return $this->response['message'];
	}
	
	/*public function serialize () {
		return serialize($this->response);
	}
	
	public function unserialize ($data) {
		$this->response = dese($data, true);
	}*/
}