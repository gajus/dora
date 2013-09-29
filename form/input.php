<?php
namespace ay\thorax\form;

class Input {
	private
		$form,
		$index,
		$attributes = ['type' => 'text'],
		$parameters,
		$multiple; // boolean Does this input represent an array? (e.g. foo[])
	
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
	
	public function getLabel () {
		if (isset($this->parameters['label'])) {
			return $this->parameters['label'];
		}
		
		return implode(array_map('ucfirst', array_filter($this->getNamePath())), ' ');
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
	private function setAttribute ($name, $value) {
		if ($name === 'name') {
			throw new \ErrorException('Name cannot be overwritten.');
		} else if (is_int($name)) {
			throw new \ErrorException('Missing parameter value.');
		}
		
		$this->attributes[$name] = $value;
	}
	
	private function getAttribute ($name) {
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
				
				#ay($this->attributes['value'], $value);
				
				if ($attributes['name'] == 'foo[checkbox_multiple][]') {
					#ay( $value, $attributes['value'] );
				}
				
				if ($attributes['value'] == $value || is_array($value) && in_array($attributes['value'], $value)) {
					$attributes['checked'] = 'checked';
				}
				
				break;
			
			case 'textarea':
				unset($attributes['type']);
				break;
		}
		
		ksort($attributes); // To make the unit testing simpler.
		
		foreach ($attributes as $k => $v) {
			$attributes_string .= ' ' . $k . '="' . $v . '"';
		}
		
		return $attributes_string;
	}
	
	public function __toString () {
		$value = $this->getValue();
		
		$attributes_string = $this->getAttributeString();
	
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
		
		return $input;
	}
}