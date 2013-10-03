<?php
namespace ay\thorax;

class Form {
	private
		$uid,
		$data = [],
		$input_index = [],
		$is_submitted = false,
		$labels = [],
		$rules = [];
		
	public function __construct (array $data = null) {
		$this->data = $data === null ? [] : $data;
		
		// Generate persistent Form UID.
		$caller = debug_backtrace(null, 1)[0];
		
		$this->uid = crc32($caller['file'] . '_' . $caller['line']);
		
		unset($caller);
		
		if (isset($_POST['thorax']['uid']) && $_POST['thorax']['uid'] == $this->getUid()) {
			unset($_POST['thorax']);
			
			$this->data = $_POST;
			
			$_SESSION['thorax']['flash']['form'][$this->getUid()] = $this->data;
			
			$this->is_submitted = true;
		} else if (isset($_SESSION['thorax']['flash']['form'][$this->getUid()])) {
			$this->data = $_SESSION['thorax']['flash']['form'][$this->getUid()];
			
			unset($_SESSION['thorax']['flash']['form'][$this->getUid()]);
		}
	}
	
	public function __destruct () {
		if ($this->isSubmitted()) {
			unset($_SESSION['thorax']['flash']['inbox'][$this->getUid()]);
		}
	}
	
	public function input ($name, array $attributes = null, array $properties = null) {
		return new form\Input($this, $name, $attributes, $properties);
	}

	public function addLabel ($template = null) {
		return new Label($this, $template);
	}
	
	public function addRule ($path, array $add = []) {
		return new Rule($this, $path, $add);
	}
	
	public function getRules () {
		return $this->rules;
	}
	
	/**
	 * @param boolean $stream
	 */
	public function getErrors ($stream = false) {
		$errors = [];
		
		foreach ($this->rules as $rule) {
			$errors = array_merge($errors, $rule->getErrors());
		}
		
		if ($stream && $errors) {
			foreach ($errors as $error) {
				$error->getInput()->pushInbox($error);
			}
		}
		
		return $errors;
	}
	
	public function getInputIndex () {
		return $this->input_index;
	}
	
	public function isSubmitted () {
		return $this->is_submitted;
	}
	
	public function getData () {
		return $this->data;
	}
	
	public function getUid () {
		return $this->uid;
	}
	
	/**
	 * While not enforced (debug_backtrace is expensive), this method
	 * is ought to be called only from Input __construct context.
	 *
	 * @return integer Incremental input name index.
	 */
	public function registerInput (form\Input $input) {
		// @todo Does the Input belong to this Form?
		
		$input_name = $input->getAttribute('name');
		
		if (!isset($this->input_index[$input_name])) {
			$this->input_index[$input_name] = [];
		} else {
			foreach ($this->input_index[$input_name] as $i) {
				if ($input === $i) {
					throw new \ErrorException('Input is already registered.');
				}
			}
		}
		
		$this->input_index[$input_name][] = $input;
		
		return count($this->input_index[$input_name]) - 1;
	}
	
	/**
	 * While not enforced (debug_backtrace is expensive), this method
	 * is ought to be called only from Rule __construct context.
	 */
	public function registerRule (Rule $rule) {
		// @todo Does the Rule belong to this Form?
		
		foreach ($this->rules as $r) {
			if ($r === $rule) {
				throw new \ErrorException('Rule is already registered.');
			}
		}
		
		$this->rules[] = $rule;
	}
}