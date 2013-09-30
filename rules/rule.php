<?php
namespace ay\thorax\rules;

class Rule {
	private
		$name,
		$body;
	
	public function __construct ($name, $body) {
		$this->name = $name;
		$this->body = $body;		
	}
	
	public function getName () {
		return $this->name;
	}
	
	public function getBody () {
		return $this->body;
	}
	
	public function isValid (\ay\thorax\form\Input $input) {
		$v8 = new \V8Js();
		
		$parameters = [
			'value' => $input->getValue()
		];
		
		$v8->parameters = $parameters;
		
		$response = $v8->executeString($this->body, null, \V8Js::FLAG_FORCE_ARRAY);
		
		return $response;
	}
}