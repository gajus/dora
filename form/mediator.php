<?php
namespace ay\thorax\form;

class Mediator {
	private
		$v8js,
		$settings = [];
	
	public function __construct () {
		$this->v8 = new \V8Js();
	}
	
	public function set ($name, $value) {
		$this->settings[$name] = $value;
	}
	
	public function get ($name) {
		return $this->settings[$name];
	}
	
	/*public function getSettings () {
		return $this->settings;
	}*/
}