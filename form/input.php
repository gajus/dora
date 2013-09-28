<?php
namespace ay\thorax\form;

class Input {
	private
		$form,
		$rules = [],
		$name,
		$name_path,
		$value,
		$label;

	public function __construct (\ay\thorax\Form $form, $name) {
		$this->form = $form;
		$this->name = $name;
		
		$path = explode('[', $name, 2);
		
		$this->name_path = [array_shift($path)];
		
		if ($path) {
			$path = mb_substr($path[0], 0, -1);
			$path = explode('][', $path);
			
			$this->name_path = array_merge($this->name_path, $path);
		}
		
		$data = $form->getData();
		
		foreach ($this->name_path as $fp) {
			if (isset($data[$fp])) {
				$data = $data[$fp];
			} else {
				$data = null;
				
				break;
			}
		}
		
		if (is_string($data)) {
			$this->value = $data;
		}
	}
	
	public function getName () {
		return $this->name;
	}
	
	public function getValue () {
		return $this->value;
	}
	
	public function getLabel () {
		return $this->label ? $this->label : ucwords(str_replace('_', ' ', end($this->name_path)));
	}
	
	public function setLabel ($label) {
		$this->label = $label;
	}

	public function __toString () {
		return '<input name="' . $this->name . '" value="' . $this->value . '">';
	}
	
	public function setType ($type) {
		
	}
	
	public function setRule (Rule $rule) {
		$name = $rule->getName();
	
		$this->rules = [$rule];
	
		return $this;
	}
	
	public function isValid () {
		$failed = [];
	
		foreach ($this->rules as $r) {
			$rule = $r->isValid($this);
			ay( $rule );
		}
	
	#	ay(  );
	
		ay( $this->rules );
	}
}