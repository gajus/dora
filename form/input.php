<?php
namespace ay\thorax\form;

class Input {
	private
		$uid,
		$form,
		$index,
		$inbox = [],
		$attributes = ['type' => null],
		$properties,
		$is_stringified = false; // boolean Has the input been casted to string?
	
	/**
	 * @param array $attributes Attributes are accessible to the Label template via getAttribute.
	 * @param array $properties Used to pass options to the <select> input. Properties are accessible to the Label template via getProperty.
	 */
	public function __construct (\ay\thorax\Form $form, $name, array $attributes = null, array $properties = null) {
		$this->attributes['name'] = $name;
		$this->form = $form;
		$this->index = $this->form->registerInput($this);
		
		// Generate persistent Input UID.
		$caller = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
		
		$this->uid = crc32($caller['file'] . '_' . $caller['line'] . '_' . $this->attributes['name'] . '_' . $this->index);
		
		unset($caller);
		
		if (isset($_SESSION['thorax']['flash']['inbox'][$this->form->getUid()][$this->getUid()])) {
			$this->inbox = $_SESSION['thorax']['flash']['inbox'][$this->form->getUid()][$this->getUid()];
		}
		
		$this->properties = $properties === null ? [] : $properties;
		
		if ($attributes) {
			foreach ($attributes as $k => $v) {
				$this->setAttribute($k, $v);
			}
		}
	}
	
	public function getForm () {
		return $this->input;
	}
	
	// ?
	public function sendError ($message) {
		$this->addToInbox( new \ay\thorax\input\Error($this, $message));
	}

	/**
	 * Inbox is a persistent layer used to convey meta data (e.g. Error object).
	 * If Input has been stringifid before the value is set, then Inbox data
	 * will be conveyd in the session until the next request.
	 *
	 * @param object $value Must be a serializable object.
	 */
	public function addToInbox (object $value) {
		$this->inbox[] = $value;
	}
	
	/*public function __destruct () {
		if ($this->form->isSubmitted()) {
			$_SESSION['thorax']['flash']['inbox'][$this->form->getUid()][$this->getUid()] = $this->inbox;
		}
	}*/
	
	public function getInbox () {
		return $this->inbox;
	}
	
	public function getUid () {
		return $this->uid;
	}
	
	public function getProperty ($name) {
		if ($name === 'label') {
			return $this->getLabel();
		}
		
		if (!isset($this->properties[$name])) {
			throw new \Exception('Unknown property "' . $name . '".');
		}
		
		return $this->properties[$name];
	}
	
	/**
	 * Label is either derived from the Input name or
	 * defined as an option at the time of creating the Input.
	 *
	 * @return string Human-friedly input name.
	 */
	private function getLabel () {
		if (isset($this->properties['label'])) {
			return $this->properties['label'];
		}
		
		$name_path = $this->getNamePath();
		
		if (strpos($this->attributes['name'], '_') !== false) {
			$temp_name_path = [];
		
			foreach ($name_path as $path) {
				$temp_name_path = array_merge($temp_name_path, explode('_', $path));
			}
			
			$name_path = $temp_name_path;
		}
		
		return implode(array_map('ucfirst', array_filter($name_path)), ' ');
	}
	
	/**
	 * @return mixed If no value is matched, will return null or (if input name implies that expected value is an array) an empty array.
	 */
	public function getValue () {
		$name_path = $this->getNamePath();
		$form_data = $this->form->getData();
		$array = false;
		
		if (strpos(strrev($this->attributes['name']), '][') === 0) { // Is this an array? e.g. foo[]
			array_pop($name_path);
			
			$array = true;
		}
		
		if ($name_path != array_filter($name_path)) {
			throw new \Exception('Unsupported multidimensional input (' . $this->attributes['name'] . '). More than one unknown dimension (e.g. foo[][bar] or foo[bar][][]).');
		}
		
		foreach ($name_path as $fp) {
			if (!isset($form_data[$fp])) {
				$form_data = null;
				
				break;	
			}
			
			$form_data = $form_data[$fp];
		}
		
		// If input type cannot handle multiple values, then use the incremented input index to get the input value.
		if ($array && !isset($this->attributes['multiple']) && isset($form_data[$this->index])) {
			$form_data = $form_data[$this->index];
		} else if ($form_data === null && isset($this->attributes['value'])) {
			$form_data = $this->attributes['value'];
		}
		
		if (!$array && is_array($form_data)) {
			throw new \Exception('Input value cannot be an array.');
		} else if (!$form_data && $array) {
			$form_data = [];
		}
		
		return $form_data;
	}
	
	/**
	 * Parse input[name] into an array reprensentation.
	 *
	 * @return array [name="a[b][c][]"] is represented ['a', 'b', 'c'].
	 */
	private function getNamePath () {
		if (strpos($this->attributes['name'], '[') === false) {
			return [$this->attributes['name']];
		}
	
		$path = explode('[', $this->attributes['name'], 2); // ['name']['[foo][bar]']
		
		return array_merge([$path[0]], explode('][', mb_substr($path[1], 0, -1)));
	}

	/**
	 * Type checking ommission is intentional. PHP does not provide scalar
	 * data type hinting. Non-scallar variables will thrown an error when
	 * cast to string. Manual validation is an unnecessary overhead.
	 *
	 * There is no value escaping either. It is assumed that pre-caution
	 * steps (e.g. FILTER_SANITIZE_SPECIAL_CHARS) are already taken.
	 *
	 * $name cannot be an integer (relavent when constructor attributes array
	 * contains only value). Do not assume that ['checked'] is checked="checked".
	 */
	public function setAttribute ($name, $value) {
		if ($this->is_stringified) {
			throw new \LogicException('Too late to set attribute value.');
		}
	
		if ($name === 'name') {
			throw new \InvalidArgumentException('Name cannot be overwritten.');
		} else if (is_int($name)) {
			throw new \InvalidArgumentException('Missing parameter value.');
		}
		
		$this->attributes[$name] = $value;
	}
	
	/**
	 * @return null|string Attribute value. In case of undefined [id], will generate a random ID.
	 */
	public function getAttribute ($name) {
		if ($name === 'id' && !isset($this->attributes['id'])) {
			if ($this->is_stringified) {
				throw new \LogicException('Too late to generate random [id].');
			}
			
			$this->attributes['id'] = 'thorax-input-id-' . mt_rand(100000, 999999);
		} else if ($name === 'value') {
			return $this->getValue();
		}
		
		return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
	}
	
	/**
	 * Generate string representation of the input attributes.
	 * Attribute string will vary depending on the input type.
	 *
	 * [value] is excluded intentionally because of the inconsistent
	 * implementation between different input types.
	 */
	private function stringifyAttributes () {
		$attributes = $this->attributes;

		$attributes_string = '';
		
		switch ($this->attributes['type']) {
			case 'checkbox':
			case 'radio':
				if (!isset($this->attributes['value'])) {
					throw new \Exception('input[type="radio"] value attribute is required.');
				}
				
				$value = $this->getValue();
				
				if ($this->attributes['value'] == $value || is_array($value) && in_array($this->attributes['value'], $value)) {
					$attributes['checked'] = 'checked';
				}
				
				break;
			
			case 'textarea':
				unset($attributes['type']);
				break;
		}
		
		unset($attributes['value']);
		
		ksort($attributes); // To make the unit testing simpler.
		
		#if (strpos(strrev($attributes['name']), '][') === 0) {
		#	$attributes['name'] = substr_replace($attributes['name'], '[' . $this->index . ']', -2);
		#}
		
		foreach ($attributes as $k => $v) {
			$attributes_string .= ' ' . $k . '="' . $v . '"';
		}
		
		return trim($attributes_string);
	}
	
	/**
	 * Find Rules that are assigned to the Form
	 * and match this Input pattern.
	 */
	public function getRules () {
		$rules = [];
	
		foreach ($this->form->getRules() as $rule) {
			if ($rule->isInputMember($this)) {
				$rules[] = $rule;
			}
		}
	
		return $rules;
	}
	
	public function stringify () {
		if ($this->is_stringified) {
			throw new \ErrorException('Input has already been stringified.');
		}
		
		$this->is_stringified = true;
		
		// Default input type is "text". If "options" property is passed,
		// then input is assumed to be <select>.
		if (isset($this->properties['options'])) {
			if (isset($this->attributes['type']) && $this->attributes['type'] !== 'select') {
				throw new \ErrorException('Unsupported parameter "options" in [input="' . $this->attributes['type'] . '"] context.');
			}
			
			$this->attributes['type'] = 'select';
		} else if (!isset($this->attributes['type'])) {
			$this->attributes['type'] = 'text';
		}
		
		$value = $this->getValue();
		
		$attributes_string = $this->stringifyAttributes();
		
		switch ($this->attributes['type']) {
			case 'select':
				if (!isset($this->properties['options'])) {
					$this->properties['options'] = [];
				}
				
				$options_string	= '';
				
				foreach ($this->properties['options'] as $v => $l) {
					$selected = '';
					
					if ((is_array($value) && in_array($v, $value) || $value == $v)) {
						$selected = ' selected="selected"';
					}
				
					$options_string	.= '<option value="' . $v . '"' . $selected . '>' . $l . '</option>';
				}
			
				$input = '<select ' . $attributes_string . '>' . $options_string . '</select>';
				break;
			
			case 'textarea':
				if (is_array($value)) {
					$value = isset($value[$this->index]) ? $value[$this->index] : '';
				}
			
				$input = '<textarea ' . $attributes_string . '>' . filter_var($value, \FILTER_SANITIZE_SPECIAL_CHARS) . '</textarea>';
				break;
		
			case 'password':
				$input = '<input ' . $attributes_string . '>';
				break;
			default:
				if (is_array($value)) {
					$value = isset($value[$this->index]) ? $value[$this->index] : '';
				}
			
				$input = '<input ' . $attributes_string . ' value="' . filter_var($value, \FILTER_SANITIZE_SPECIAL_CHARS) . '">';
				break;
		}
		
		// In case of multiple forms on the page, thorax[uid] is used to catch specific form submit event.
		if ($this->attributes['type'] === 'submit') {
			//$input = $input . '<input type="hidden" name="thorax[uid]" value="' . $this->form->getUid() . '">';
		}
				
		return $input;
	}
	
	public function __toString () {
		return $this->stringify();
	}
}