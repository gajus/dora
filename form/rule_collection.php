<?php
namespace ay\thorax\form;

class Rule_Collection {
	private
		$registered = [];
		
	public function __construct (/* @todo $load_default */) {
		// Predefined rules
		
		foreach (glob(__DIR__ . '/rules/*.js') as $rule) {
			$name = 'thorax::' . pathinfo($rule)['filename'];
		
			$this->registered[$name] = new Rule($name, file_get_contents($rule));
		}
	}

	public function getRule ($name) {
		if (!isset($this->registered[$name])) {
			throw new \ErrorException('Requested for unrecognised rule.');
		}
		
		return $this->registered[$name];
	}
	
	public function hasRule ($name) {
		return isset($this->registered[$name]);
	}
}