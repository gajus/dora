<?php
namespace gajus\thorax;

class Form {
	private
		/**
		 * Quasi-persistent unique indentifier. This UID does not change unless
		 * the underlying code has changed, i.e. UID is derived using the hash
		 * of the caller file/line.
		 * 
		 * @param string
		 */
		$uid,
		/**
		 * Input assigned to the form. This data is used together with input_index
		 * to determine the representable input value.
		 *
		 * @param array
		 */
		$data = [],
		/**
		 * Index of all inputs generated using this form instance.
		 * ['input_name' => [instance1, instance2, ..], ..]
		 *
		 * @param array
		 */
		$input_index = [],
		/**
		 * @param boolean
		 */
		$is_submitted = false;
		
	/**
	 * @param array $default_data Data used to prefill the form.
	 * @param array $input Input data will overwrite $default_data. Defaults to $_POST.
	 */
	public function __construct (array $default_data = null, array $input = null) {
		$caller = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
		
		$this->uid = crc32($caller['file'] . '_' . $caller['line']);
		
		unset($caller);

		if ($input === null) {
			$input = $_POST;
		}
		
		// Capture submit event only when the form UID is present.
		if (isset($input['thorax']['uid']) && $input['thorax']['uid'] == $this->getUid()) {
			unset($input['thorax']);
			
			$_SESSION['thorax']['flash']['form'][$this->getUid()] = $input;
			
			$this->data = $input;
			$this->is_submitted = true;
		
		// Loaded page has meta-data about the previous request.
		} else if (isset($_SESSION['thorax']['flash']['form'][$this->getUid()])) {
			$this->data = $_SESSION['thorax']['flash']['form'][$this->getUid()];
			
			unset($_SESSION['thorax']['flash']['form'][$this->getUid()]);
		} else if ($default_data !== null) {
			$this->data = $default_data;
		}
	}

	public function getTranslator () {
		return $this->translator;
	}
	
	public function __destruct () {
		/*if ($this->isSubmitted()) {
			unset($_SESSION['thorax']['flash']['inbox'][$this->getUid()]);
		}*/
	}
	
	public function input ($name, array $attributes = null, array $properties = null) {
		return new form\Input($this, $name, $attributes, $properties);
	}

	public function addLabel ($template = null) {
		return new Label($this, $template);
	}
		
	/*public function isError () {
		$errors = [];
	
		foreach ($this->getRules() as $rule) {
			foreach ($rule->getInputIndex() as $input) {
				// Unless input rule implies array, this refers to the first occurence of the input.
				
				$rule_class = $rule->getRule();
				$rule_instance = new $rule_class($input[0]);
				
				if (!$rule_instance->isValid()) {
					$errors[] = $rule_instance->getMessage();
				}
			}
		}
		
		return $errors;
	}*/
	
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
	 * This method is ought to be called only from Input __construct context.
	 *
	 * @return integer Incremental input index based on the number of previous occurences of Input with the same name within the Form.
	 */
	public function registerInput (form\Input $input) {
		$caller = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
		
		if ($caller['function'] !== '__construct' || $caller['class'] !== 'gajus\thorax\form\Input') {
			throw new \InvalidArgumentException('Input must be initiated under the Form that you are trying to associate the Input with.');
		}
		
		unset($caller);
		
		$input_name = $input->getAttribute('name');
		
		if (!isset($this->input_index[$input_name])) {
			$this->input_index[$input_name] = [];
		} else {
			foreach ($this->input_index[$input_name] as $i) {
				if ($input === $i) {
					throw new \Exception('Input is already registered.');
				}
			}
		}
		
		$this->input_index[$input_name][] = $input;
		
		return count($this->input_index[$input_name]) - 1;
	}
	
	/**
	 * This method is ought to be called only from Rule __construct context.
	 */
	public function registerRule (Rule $rule) {
		$caller = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
		
		if ($caller['function'] !== '__construct' || $caller['class'] !== 'gajus\thorax\Rule') {
			throw new \InvalidArgumentException('Rule must be initiated under the Form that you are trying to associate the Rule with.');
		}
		
		unset($caller);
		
		foreach ($this->rules as $r) {
			if ($r === $rule) {
				throw new \Exception('Rule is already registered.');
			}
		}
		
		$this->rules[] = $rule;
	}
}