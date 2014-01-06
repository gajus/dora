<?php
namespace ay\thorax;

class Rule {
	private
		$form,
		$rule,
		$selector_index = [];
		
	/**
	 * @param Closure $callback First parameter will be the input value or null if it is not set. Callback must return true if the rule passed.
	 * @param boolean $inverse_boolean If set to true, will require $callback to return false if the rule passed.
	 * @param string $scope 'single' will apply to a single input from the same index group, 'multiple' – applies to all inputs, 'group' – rule is applied on a group.
	 */
	public function __construct (Form $form, array $input_selector, $rule, $scope = 'single') {
		$this->form = $form;
		$this->form->registerRule($this);
		
		$this->addSelector($input_selector);
		
		if (!class_exists($rule) || !is_subclass_of($rule, 'ay\thorax\User_Rule')) {
			throw new \InvalidArgumentException('Rule must be name of a class extending ay\thorax\User_Rule.');
		}
		
		$this->rule = $rule;
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
	
	public function getInputIndex () {
		$index = $this->form->getInputIndex();
		
		return _array_intersect_ukey($index, array_flip($this->selector_index), function ($a, $b) {
			if (strpos($b, '/') === 0) {
				return preg_match($b, $a) > 0 ? 0 : -1;
			}
			
			return $a == $b ? 0 : -1;
		});
	}
	
	public function getRule () {
		return $this->rule;
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