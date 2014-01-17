<?php
namespace gajus\dora;

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
	 * @param array $default_data Data used if $input does not contain instance UID.
	 * @param array $input Input data will overwrite $default_data. Defaults to $_POST.
	 */
	public function __construct (array $default_data = null, array $input = null) {
		$caller = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];

		$this->uid = crc32($caller['file'] . '_' . $caller['line']);

		unset($caller);

		if ($input === null) {
			$input = $_POST;
		}


		$this->is_submitted = !!$input;
		#$this->is_submitted = isset($input['dora']['uid']) && $input['dora']['uid'] == $this->getUid();
		
		unset($input['dora']);

		#var_dump($default_data, $input);

		if ($this->is_submitted) {
			$_SESSION['dora']['flash']['form'][$this->getUid()] = $input;
			
			$this->data = $input;		
		} else if (isset($_SESSION['dora']['flash']['form'][$this->getUid()])) {
			$this->data = $_SESSION['dora']['flash']['form'][$this->getUid()];
			
			unset($_SESSION['dora']['flash']['form'][$this->getUid()]);
		} else if ($default_data !== null) {
			$this->data = $default_data;
		}
	}

	public function input ($name, array $attributes = [], array $properties = []) {
		$input = new Input($name, $attributes, $properties);

		if (isset($properties['value'])) {
			throw new \InvalidArgumentException('Input instantiated using Form::input() method cannot explicitly define "value" property.');
		}

		if (!isset($this->input_index[$name])) {
			$this->input_index[$name] = [];
		}

		$index = count($this->input_index[$name]);

		$this->input_index[$name][] = $input;

		// Resolve input value
		$path = $input->getNamePath();

		$value = $this->data;

		// Indicates whether input name attribute implies that expected value is an array, e.g. foo[].
		$declared_as_array = false;
		
		if (strpos(strrev($name), '][') === 0) {
			array_pop($path);
			
			$declared_as_array = true;
		}

		foreach ($path as $crumble) {
			if (!isset($value[$crumble])) {
				$value = null;
				
				break;
			}
			
			$value = $value[$crumble];
		}

		if (is_array($value)) {
			if (!$declared_as_array) {
				$value = null;
			} else if (isset($attributes['multiple'])) {
				$value = $value;
			} else if (isset($value[$index])) {
				$value = $value[$index];
			} else {
				$value = null;
			}
		} else if ($declared_as_array) {
			$value = null;
		}

		$input->setProperty('value', $value);

		return $input;
	}

	#public function addLabel ($template = null) {
	#	return new Label($this, $template);
	#}
		
	#public function getInputIndex () {
	#	return $this->input_index;
	#}
	
	#public function isSubmitted () {
	#	return $this->is_submitted;
	#}

	#public function getInputData ($name, $index = 0) {
	#
	#}
	
	public function getData () {
		return $this->data;
	}
	
	/**
	 * @return string
	 */
	public function getUid () {
		return $this->uid;
	}
	
	/**
	 * This method is ought to be called only from Input __construct context.
	 *
	 * @return integer Incremental input index based on the number of previous occurences of Input with the same name within the Form.
	 */
	#public function registerInput (form\Input $input) {	
	#	$input_name = $input->getAttribute('name');
	#	
	#	if (!isset($this->input_index[$input_name])) {
	#		$this->input_index[$input_name] = [];
	#	} else {
	#		foreach ($this->input_index[$input_name] as $i) {
	#			if ($input === $i) {
	#				throw new \Exception('Input is already registered.');
	#			}
	#		}
	#	}
	#	
	#	$this->input_index[$input_name][] = $input;
	#	
	#	return count($this->input_index[$input_name]) - 1;
	#}
}