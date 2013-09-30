<?php
namespace ay\thorax;

class Rule {
	private
		$form,
		$rule,
		$index = [];

	public function __construct (Form $form, $path = 'is_eq_a') {
		$this->form = $form;
		
		$this->load($path);
	}
	
	public function load ($requested_path) {
		if (strpos($requested_path, '/') !== 0) {
			$path = __DIR__ . '/rules/library/' . $requested_path;
		}
		
		if (strpos(strrev($path), 'sj.') === false && file_exists($path . '.js')) {
			$path .= '.js';
		}
		
		if (!file_exists($path)) {
			throw new \ErrorException('Rule "' . $requested_path . '" not found.');
		}
	
		$filename = pathinfo($path)['filename'];
			
		$this->rule = new rules\Rule($filename, file_get_contents($path));
	}
	
	/**
	 * @param string $input_name Name begining with a "/" (backslash) will be interpreted as regular-expression.
	 */
	public function add ($input_name) {
		if (is_array($input_name)) {
			foreach ($input_name as $in) {
				$this->add($in);
			}
			
			return;
		}
	
		$this->index[] = $input_name;
		
		$this->index = array_unique($this->index);
	}
	
	public function getSubject () {
		$index = $this->form->getInputIndex();
		
		return _array_intersect_ukey($index, array_flip($this->index), function ($a, $b) {
			if (strpos($b, '/') === 0) {
				return preg_match($b, $a) > 0 ? 0 : -1;
			}
			
			return $a != $b;
		});
	}
	
	//public function getInvalidInput () {
	public function getErrors () {
		$subject = $this->getSubject();
		
		$failed_test = [];
		
		foreach ($subject as $input) {
			#foreach ($inputs as $input) {
				if (($test = $this->rule->isValid($input[0])) && !$test['passed']) {
					$failed_test[] = $input[0];
				}
			#}
		}
		
		return $failed_test;
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