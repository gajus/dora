<?php
namespace ay\thorax\form;

class Input {
	private
		$form,
		$index,
		$attributes = ['type' => null],
		$parameters,
		$multiple, // boolean Does this input represent an array? (e.g. foo[])
		$displayed = false; // boolean Has the input been casted to string?
	
	/**
	 * @param array $parameters Used to pass options to the <select> input.
	 * @param array $index Used to assign value when there is an input representing array data (e.g. foo[]).
	 */
	public function __construct (\ay\thorax\Form $form, $name, array $attributes = null, array $parameters = null, $index = 0) {
		$this->form = $form;
		$this->attributes['name'] = $name;
		$this->index = $index;
		$this->parameters = $parameters === null ? [] : $parameters;
		
		$this->multiple = strpos(strrev($name), '][') === 0;
		
		if ($attributes === null) {
			return;
		}
		
		foreach ($attributes as $k => $v) {
			$this->setAttribute($k, $v);
		}
	}
	
	public function getId () {
		if (isset($this->attributes['id'])) {
			return $this->attributes['id'];
		}
		
		if ($this->displayed) {
			throw new \ErrorException('input[id] was not defined at the time of creating the input. Too late to generate random [id].');
		}
		
		$this->attributes['id'] = 'thorax-input-id-' . mt_rand(100000,999999);
		
		return $this->attributes['id'];
	}
	
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
	
	private function getValue () {
		$name_path = $this->getNamePath();
		$form_data = $this->form->getData();
		
		if ($this->multiple) {
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
	 * [name="a[b][c]"] is converted to array ['a', 'b', 'c'].
	 */
	private function getNamePath () {
		$path = explode('[', $this->attributes['name'], 2);
		
		$name_path = [array_shift($path)];
		
		if ($path) {
			$path = mb_substr($path[0], 0, -1);
			$path = explode('][', $path);
			
			$name_path = array_merge($name_path, $path);
		}
		
		return $name_path;
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
		if ($this->displayed) {
			throw new \ErrorException('Too late to set attribute value.');
		}
	
		if ($name === 'name') {
			throw new \ErrorException('Name cannot be overwritten.');
		} else if (is_int($name)) {
			throw new \ErrorException('Missing parameter value.');
		}
		
		$this->attributes[$name] = $value;
	}
	
	public function getAttribute ($name) {
		return $this->attributes[$name];
	}
	
	public function getAttributeString () {
		$attributes = $this->attributes;

		$attributes_string = '';
		
		switch ($this->attributes['type']) {
			case 'checkbox':
			case 'radio':
				if (!isset($this->attributes['value'])) {
					throw new \ErrorException('input[type="radio"] value attribute is required.');
				}
				
				$value = $this->getValue();
				
				#if ($attributes['name'] == 'foo[checkbox_multiple][]') {
					#ay( $value, $attributes['value'] );
				#}
				
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
		
		return $attributes_string;
	}
	
	public function __toString () {
		$this->displayed = true;
		
		if (array_key_exists('options', $this->parameters)) {
			if (isset($this->attributes['type']) && $this->attributes['type'] !== 'select') {
				throw new \ErrorException('Unsupported parameter "options" in [input="' . $this->attributes['type'] . '"] context.');
			}
			
			$this->attributes['type'] = 'select';
		} else if (!isset($this->attributes['type'])) {
			$this->attributes['type'] = 'text';
		}
		
		if ($this->attributes['type'] === 'submit' && $this->index !== 0) {
			throw new \ErrorException('Every input[type="submit"] must have a unique name within the form.');
		}
		
		$value = $this->getValue();
		
		$attributes_string = trim($this->getAttributeString());
		
		switch ($this->attributes['type']) {
			case 'select':
				if (!isset($this->parameters['options'])) {
					$this->parameters['options'] = [];
				}
				
				#if ($this->parameters['name'] ===)
				
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
		
		if ($this->attributes['type'] === 'submit') {
			$caller = debug_backtrace()[0]; // Where was __toString triggered?
			
			$uid = crc32($caller['file'] . '_' . $caller['line'] . '_' . $this->attributes['name']);
			
			$this->attributes['thorax_uid'] = $uid;
			
			$input = '<input type="hidden" name="thorax[submit]" value="' . $uid . '">' . $input;
		}
		
		return $input;
	}
}