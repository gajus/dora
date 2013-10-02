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
	
	/**
	 * @returns boolean|\ay\thorax\Error
	 */
	public function getError (\ay\thorax\form\Input $input) {
		$v8 = new \V8Js();
		
		$parameters = [
			'value' => $input->getAttribute('value')
		];
		
		$v8->parameters = $parameters;
		
		$response = $v8->executeString($this->body, null, \V8Js::FLAG_FORCE_ARRAY);
		
		if (!array_key_exists('passed', $response)) {
			throw new \ErrorException('Invalid rule. Missing "passed" property.');
		}
		
		if (!array_key_exists('message', $response)) {
			throw new \ErrorException('Invalid rule. Missing "message" property.');
		}
		
		if ($response['passed']) {
			return false;
		}
		
		$response['message'] = str_replace(['{thorax.label}'], [$input->getProperty('label')], $response['message']);
		
		#ay($this, $input, $response);
		
		return new \ay\thorax\input\Error($input, $response);
	}
}