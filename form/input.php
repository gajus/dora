<?php
namespace ay\thorax\form;

class Input {
	private
		$uid,
		$form,
		$index,
		$inbox = [],
		$attributes = ['type' => null],
		$parameters,
		$is_stringified = false; // boolean Has the input been casted to string?
	
	/**
	 * @param array $parameters Used to pass options to the <select> input.
	 */
	public function __construct (\ay\thorax\Form $form, $name, array $attributes = null, array $parameters = null) {
		$this->attributes['name'] = $name;
		
		$this->form = $form;
		
		$this->index = $this->form->registerInput($this);
		
		// Generate persistent Input UID.
		$caller = debug_backtrace(null, 1)[0];
		
		$this->uid = crc32($caller['file'] . '_' . $caller['line'] . '_' . $this->attributes['name'] . '_' . $this->index);
		
		unset($caller);
		
		if (isset($_SESSION['thorax']['flash']['inbox'][$this->form->getUid()][$this->getUid()])) {
			$this->inbox = $_SESSION['thorax']['flash']['inbox'][$this->form->getUid()][$this->getUid()];
			
			unset($_SESSION['thorax']['flash']['inbox'][$this->form->getUid()][$this->getUid()]);
		}
		
		$this->parameters = $parameters === null ? [] : $parameters;
		
		if ($attributes === null) {
			return;
		}
		
		foreach ($attributes as $k => $v) {
			$this->setAttribute($k, $v);
		}
	}

	/**
	 * Inbox is used to convey meta data (e.g. Error object). If Input has not been
	 * displayed at the time of pushing a new value, then the value will be kept in
	 * the session until the next time the page is loaded.
	 *
	 * @param object $value
	 */
	public function pushInbox ($value) {
		#if (!is_object($value)) {
		#	throw new \InvalidArgumentException('Only serializable object type value can be passed.');
		#}
		
		if ($this->is_stringified) {
			$_SESSION['thorax']['flash']['inbox'][$this->form->getUid()][$this->getUid()][] = $value;
		} else {
			$this->inbox[] = $value;
		}
	}
	
	/**
	 * @return array
	 */
	public function getInbox () {
		if (!isset($_SESSION['thorax']['flash']['inbox'][$this->form->getUid()][$this->getUid()])) {
			return $this->inbox;
		}
		
		return array_merge($this->inbox, $_SESSION['thorax']['flash']['inbox'][$this->form->getUid()][$this->getUid()]);
	}
	
	public function getUid () {
		return $this->uid;
	}
	
	/**
	 * @return string Human-friedly input name. Label is either derived
	 * from the Input name or defined at the time of creating the Input.
	 */
	public function getLabel () {
		if (isset($this->parameters['label'])) {
			return $this->parameters['label'];
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
	
	public function getValue () {
		$name_path = $this->getNamePath();
		$form_data = $this->form->getData();
		
		if (strpos(strrev($this->attributes['name']), '][') === 0) { // Is this an array? e.g. foo[]
			array_pop($name_path);
		}
		
		if ($name_path != array_filter($name_path)) {
			throw new \ErrorException('Unsupported multidimensional input format.');
		}
		
		foreach ($name_path as $fp) {
			if (isset($form_data[$fp])) {
				$form_data = $form_data[$fp];
			} else {
				$form_data = null;
				
				break;
			}
		}
		
		if ($form_data === null && isset($this->attributes['value'])) {
			$form_data = $this->attributes['value'];
		}
		
		return $form_data;
	}
	
	/**
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
			throw new \ErrorException('Too late to set attribute value.');
		}
	
		if ($name === 'name') {
			throw new \ErrorException('Name cannot be overwritten.');
		} else if (is_int($name)) {
			throw new \ErrorException('Missing parameter value.');
		}
		
		$this->attributes[$name] = $value;
	}
	
	/**
	 * @return string Attribute value. In case of undefined [id], will generate a random ID.
	 */
	public function getAttribute ($name) {
		if ($name === 'id' && !isset($this->attributes['id'])) {
			if ($this->is_stringified) {
				throw new \ErrorException('input[id] was not defined at the time of creating the input. Too late to generate random [id].');
			}
			
			$this->attributes['id'] = 'thorax-input-id-' . mt_rand(100000,999999);
		}
		
		if (!isset($this->attributes[$name])) {
			return null;
		}
		
		return $this->attributes[$name];
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
					throw new \ErrorException('input[type="radio"] value attribute is required.');
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
	
	public function __toString () {
		if ($this->is_stringified) {
			throw new \ErrorException('Input has already been stringified.');
		}
		
		$this->is_stringified = true;
		
		// Default input type is "text". If "options" parameter is passed,
		// input is assumed to be <select>.
		if (array_key_exists('options', $this->parameters)) {
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
				if (!isset($this->parameters['options'])) {
					$this->parameters['options'] = [];
				}
				
				$options_string	= '';
				
				foreach ($this->parameters['options'] as $v => $l) {
					$selected = '';
					
					if ($value && (is_array($value) && in_array($v, $value) || $value == $v)) {
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
		
		// Since there may be more than one form on the same page,
		// "thorax[uid]" is used to catch specific form submit event.
		if ($this->attributes['type'] === 'submit') {
			$input = '<input type="hidden" name="thorax[uid]" value="' . $this->form->getUid() . '">' . $input;
		}
				
		return $input;
	}
}