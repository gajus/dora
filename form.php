<?php
namespace ay\thorax;

class Form {
	private
		$uid,
		$uid_index = 0,
		$data = [],
		$input_index = [];
	
	public function __construct (array $data = null) {
		$this->data = $data === null ? [] : $data;
		
		$caller = debug_backtrace()[0]; // Where was __toString triggered?
		
		$this->uid = 'thorax-' . crc32($caller['file'] . '_' . $caller['line']);
		
		if (isset($_POST['thorax']['uid']) && $_POST['thorax']['uid'] === $this->uid) {
			unset($_POST['thorax']);
			
			$this->data = array_merge_recursive($this->data, $_POST);
		} else if (isset($_GET['thorax']['uid']) && $_GET['thorax']['uid'] === $this->uid) {
			unset($_GET['thorax']);
			
			$this->data = array_merge_recursive($this->data, $_GET);
		}
		
		// Prevent accidental output buferring.
		ob_start();
	}
	
	public function input ($name, array $attributes = null, array $parameters = null) {
		if (!isset($this->input_index[$name])) {
			$this->input_index[$name] = [];
		}
		
		$input = new form\Input($this, $name, $attributes, $parameters, count($this->input_index[$name]));
		
		$this->input_index[$name][] = $input;
		
		return $input;
	}
	
	public function getData () {
		return $this->data;
	}
	
	public function getUid () {
		return $this->uid;
	}
	
	public function getUidIndex () {
		return $this->uid_index++;
	}
}