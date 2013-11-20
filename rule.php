<?php
namespace ay\thorax;

class Rule {
	private
		$form,
		$rule,
		$pattern_index = [];

	public function __construct (Form $form, $rule_name, $input_selector) {
		$this->form = $form;
		$this->form->registerRule($this);
		
		$this->loadRule($rule_name);
		
		$this->addSelector($input_selector);
	}
	
	private function loadRule ($rule_name) {
		if (strpos($rule_name, '/') !== 0) {
			$path = __DIR__ . '/rules/library/' . $rule_name;
		} else {
			$path = $rule_name;
		}
		
		if (strpos(strrev($path), 'sj.') === false && file_exists($path . '.js')) {
			$path .= '.js';
		}
		
		if (!file_exists($path)) {
			throw new \Exception('Rule "' . $rule_name . '" not found.');
		}
	
		$filename = pathinfo($path)['filename'];
			
		$this->rule = new rules\Rule($filename, file_get_contents($path));
	}
	
	/**
	 * @param string|array $input_selector Match input using [name]. Selector begining with a "/" (backslash) will be interpreted as a regular-expression.
	 */
	public function addSelector ($input_selector) {
		if (is_array($input_selector)) {
			foreach ($input_selector as $is) {
				$this->addSelector($is);
			}
			
			return;
		}
	
		$this->selector_index[] = $input_selector;
	}
	
	public function getName () {
		return $this->rule->getName();
	}
	
	public function getSelector () {
		return $this->selector_index;
	}
	
	public function getFunction () {
		return $this->rule->getFunction();
	}
	
	public function isInputMember (form\Input $input) {
		$input_name = $input->getAttribute('name');
		
		foreach ($this->selector_index as $selector) {
			if ($selector === $input_name || strpos($pattern, '/') === 0 && preg_match($selector, $input_name)) {
				return true;
			}
		}
		
		return false;
	}
	
	public function getInputIndex () {
		$index = $this->form->getInputIndex();
		
		return _array_intersect_ukey($index, array_flip($this->pattern_index), function ($a, $b) {
			if (strpos($b, '/') === 0) {
				return preg_match($b, $a) > 0 ? 0 : -1;
			}
			
			return $a == $b ? 0 : -1;
		});
	}
	
	public function getErrors () {
		$subject = $this->getInputIndex();
		
		$errors = [];
		
		foreach ($subject as $inputs) {
			foreach ($inputs as $input) {
				if ($error = $this->rule->getError($input)) {
					$errors[] = $error;
				}
			}
		}
		
		return $errors;
	}
}

/**
 * @author Gajus Kuizinas <gk@anuary.com>
 * @version 1.0.0 (2013 09 30)
 * @url https://gist.github.com/gajus/271ad5f36337a32a184c
 */
function _array_intersect_ukey (array $arr1, array $arr2, $key_compare_func) {
	$arr_matched = [];
	$arr_unmatched = [];
	
	$args = func_get_args();
	
	$key_compare_func = end($args);
	
	foreach ($arr1 as $k1 => $v1) {
		foreach ($arr2 as $k2 => $v2) {
			$diff = $key_compare_func($k1, $k2);
		
			//var_dump('k1=' . $k1 . ', k2=' . $k2 . ', diff=' . $diff);
			
			if ($diff === 0) {
				$arr_matched[$k1] = $v1;
			} else {
				$arr_unmatched[$k1] = $v1;
			}
		}
	}
	
	if (count($args) <= 3) {
		return $arr_matched;
	}
	
	array_splice($args, 0, 2, [$arr_unmatched]);
	
	return array_merge($arr_matched, call_user_func_array('_array_intersect_ukey', $args));
}