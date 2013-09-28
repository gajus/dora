<?php
namespace ay\thorax\form;

class Rule {
	private
		$name,
		$body,
		$message;
	
	public function __construct ($name, $body, $message = null) {
		$this->name = $name;
		$this->body = $body;
		
		$v8 = new \V8Js();
		
		if ($message === null) {
			$settings = $v8->executeString($body, null, \V8Js::FLAG_FORCE_ARRAY);
			
			if (!isset($settings['message'])) {
				throw new \ErrorExplorer('Missing rule (' . $name . ') message.');
			}
			
			$this->message = $settings['message'];
		} else {
			$this->message = $message;
		}
	}
	
	public function setMessage ($message) {
		$this->message = $message;
	}
	
	public function getName () {
		return $this->name;
	}
	
	public function getBody () {
		return $this->body;
	}
	
	public function getMessage () {
		return $this->message;
	}
	
	public function isValid (Input $input) {
		$v8 = new \V8Js();
		
		$parameters = [
				'name' => $input->getName(),
				'label' => $input->getLabel(),
				'value' => $input->getValue(),
				'message' => $this->getMessage()
			];
			
		
		$parameters['message'] = str_replace('{label}', $parameters['label'], $parameters['message']);
		
		
		$v8->parameters = $parameters;
		
		$response = $v8->executeString($this->body, null, \V8Js::FLAG_FORCE_ARRAY);
		
		return $response;
	}
}