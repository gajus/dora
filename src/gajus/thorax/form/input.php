<?php
namespace gajus\thorax\form;

class Input {
	private
		/**
		 * Quasi-persistent unique indentifier. This UID does not change unless
		 * the underlying code has changed, i.e. UID is derived using the hash
		 * of the instance attributes, index and the caller file/line.
		 *
		 * @param string
		 */
		$uid,
		/**
		 * Instance of the Form that created the Input.
		 *
		 * @param Form
		 */
		$form,
		/**
		 * Incremental index assigned based on the previous occurence of
		 * input with the same name within the initiating form instance.
		 *
		 * @param integer
		 */
		$index,
		/**
		 * HTML attributes.
		 * Attributes are accessible to the Label template via getAttribute.
		 * 
		 * @param array
		 */
		$attributes = [],
		/**
		 * Input properties specific to the input type, e.g. 'options' property is
		 * used together with the <select> input.
		 * Properties are accessible to the Label template via getProperty.
		 *
		 * @param array
		 */
		$properties = [],
		/**
		 * Value is used to determine whether input has been casted to string.
		 * After input is casted to string, some operations are no longer possible, e.g.
		 * generating a new random ID.
		 * 
		 * @param boolean
		 */
		$is_stringified = false;
	
	/**
	 * @param \gajus\Thorax\Form $form Instance of the Form that created the Input.
	 * @param string $name Input name.
	 * @param array $attributes HTML attributes.
	 * @param array $properties Input type specific properties.
	 */
	public function __construct (\gajus\thorax\Form $form, $name, array $attributes = null, array $properties = null) {
		$this->attributes['name'] = $name;
		$this->form = $form;
		$this->index = $this->form->registerInput($this);
		
		// Generate persistent Input UID.
		$caller = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
		
		$this->uid = crc32($caller['file'] . '_' . $caller['line'] . '_' . $this->attributes['name'] . '_' . $this->index);
		
		unset($caller);
		
		$this->properties = $properties === null ? [] : $properties;

		if (!isset($this->properties['name'])) {
			// Input name is either derived from the Input attribute name.
			$this->properties['name'] = ucwords(implode(' ', explode('_', implode('_', $this->getNamePath()))));
		}
		
		if ($attributes) {
			foreach ($attributes as $k => $v) {
				$this->setAttribute($k, $v);
			}
		}
	}
	
	/**
	 * @return string
	 */
	public function getUid () {
		return $this->uid;
	}
	
	/**
	 * @param string $name Name of the property.
	 * @return mixed
	 */
	public function getProperty ($name) {
		if (!isset($this->properties[$name])) {
			throw new \Exception('Requested for undefined property "' . $name . '".');
		}
		
		return $this->properties[$name];
	}
	
	/**
	 * @return mixed If no value is matched, will return null or (if input name implies that expected value is an array) an empty array.
	 */
	public function getValue () {
		$name_path = $this->getNamePath();
		$input_value = $this->form->getData();
		
		// Indicates whether input name attribute implies that expected value is an array, e.g. foo[].
		$is_array = false;
		// Indicate whether input value is found within the form input.
		$is_found = true;
		
		if (strpos(strrev($this->attributes['name']), '][') === 0) {
			array_pop($name_path);
			
			$is_array = true;
		}

		if ($name_path != array_filter($name_path)) {
			throw new \Exception('Unsupported multidimensional input (' . $this->attributes['name'] . '). More than one unknown dimension (e.g. foo[][bar] or foo[bar][][]).');
		}
		
		foreach ($name_path as $np) {
			if (!isset($input_value[$np])) {
				$is_found = false;
				$input_value = null;
				
				break;
			}
			
			$input_value = $input_value[$np];
		}
		
		// If input type cannot handle multiple values, then use input index to get the input value.
		// @todo See if multiple attribute requires that name has [] array declaration.
		if ($is_array && !isset($this->attributes['multiple']) && isset($input_value[$this->index])) {
			$input_value = $input_value[$this->index];
		} else if (!$is_found && isset($this->attributes['value'])) {
			// @todo This should be used only when value is not in "is_submitted" state.
			$input_value = $this->attributes['value'];
		}
		
		if (!$is_array && is_array($input_value)) {
			throw new \Exception('Input value cannot be an array.');
		} else if (!$input_value && $is_array) {
			$input_value = [];
		}
		
		return $input_value;
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
	 * If [id] is undefined at the time of request, Thorax will use input UID.
	 * Do not rely on the UID for selecting the element in the frontend code. 
	 *
	 * @return null|string Attribute value.
	 */
	public function getAttribute ($name) {
		if ($name === 'id' && !isset($this->attributes['id'])) {
			if ($this->is_stringified) {
				throw new \LogicException('Too late to generate random [id].');
			}
			
			$this->attributes['id'] = 'thorax-input-' . $this->getUid();
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
	
	public function stringify () {
		if ($this->is_stringified) {
			throw new \Exception('Input has already been stringified.');
		}
		
		$this->is_stringified = true;
		
		// Default input type is "text". If "options" property is present, then input is assumed to be <select>.
		if (isset($this->properties['options'])) {
			if (isset($this->attributes['type']) && $this->attributes['type'] !== 'select') {
				throw new \Exception('Unsupported property "options" in [input="' . $this->attributes['type'] . '"] context.');
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
					
					// @todo Check if input has "multiple" attribute.
					if ((is_array($value) && in_array($v, $value) || $value == $v)) {
						$selected = ' selected="selected"';
					}
				
					$options_string	.= '<option value="' . $v . '"' . $selected . '>' . $l . '</option>';
				}
			
				$input = '<select ' . $attributes_string . '>' . $options_string . '</select>';
				break;
			
			case 'password':
				$input = '<input ' . $attributes_string . '>';
				break;

			default:
				if (is_array($value)) {
					$value = isset($value[$this->index]) ? $value[$this->index] : '';
				}

				if ($this->attributes['type'] === 'textarea') {
					$input = '<textarea ' . $attributes_string . '>' . filter_var($value, \FILTER_SANITIZE_SPECIAL_CHARS) . '</textarea>';
				} else {
					$input = '<input ' . $attributes_string . ' value="' . filter_var($value, \FILTER_SANITIZE_SPECIAL_CHARS) . '">';
				}
				break;
		}
		
		// In case of multiple forms on the page, thorax[uid] is used to catch specific form submit event.
		if ($this->attributes['type'] === 'submit') {
			$input = $input . '<input type="hidden" name="thorax[uid]" value="' . $this->form->getUid() . '">';
		}
				
		return $input;
	}
	
	public function __toString () {
		return $this->stringify();
	}
}