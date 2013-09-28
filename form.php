<?php
namespace ay\thorax;

class Form {
	private
		$form = [],
		//$rules = [],
		$rule_collection,
		$data,
		$id;
	
	public function __construct (/*form\Rule_Collection $rule_collection = null, array $data = null*/) {
		/*$caller = debug_backtrace()[1];
		
		$this->id = 'thorax-form-' . crc32($caller['file'] . '_' . $caller['line']);
		$this->data = $data === null ? $_POST : $data;
		
		if ($rule_collection === null) {
			$this->rule_collection = new form\Rule_Collection();
		}*/
	}
	
	#public function setData () {
	#	
	#}
	
	#public function setId ($id) {
	#	
	#}
	
	public function getData () {
		return $this->data;
	}

	public function getId () {
		return $this->id;
	}

	/*public function input ($name, $label) {
		return $this->getInput($name, $label);
	}*/
	
	/**
	 * @param string $name Rule name.
	 * @param array $input Array of input names.
	 */
	public function setRule ($rule_name, $input_name, $error_message = null) { // @todo Add array $input support
		$input = $this->getInput($input_name);
		
		if (!$this->rule_collection->hasRule($rule_name)) {
			throw new \ErrorException('Rule does not exist (' . $rule_name . ').');
		}
		
		$rule = $this->rule_collection->getRule($rule_name);
		
		if ($error_message !== null) {
			$rule->setMessage($error_message);
		}
		
		$input->setRule( $rule );
	}
	
	public function isValid () {
		$valid = true;
	
		foreach ($this->form as $input) {
			ay( $input->isValid()['passed'] );
		}
	
		/*$valid = true;
		
		foreach ($this->rules as $input_name => $rules) {
			#ay( $this->rule_collection->getRule($r)->isValid() );
		}
		
		return $valid;*/
	}
	
	/*private function getInput ($name, $label = null) {
		if (!isset($this->form[$name])) {
			$this->form[$name] = new form\Input($this, $name);
		}
		
		if ($label !== null) {
			$this->form[$name]->setLabel($label);
		}
		
		return $this->form[$name];
	}*/
}