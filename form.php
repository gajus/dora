<?php
namespace ay\thorax;

class Form {
	private
		$uid,
		$uid_index = 0,
		$data = [],
		$input_index = [],
		$is_submitted = false;
	
	public function __construct (array $data = null) {
		$this->data = $data === null ? [] : $data;
		
		$caller = debug_backtrace()[0]; // Where was __toString triggered?
		
		$this->uid = crc32($caller['file'] . '_' . $caller['line']);
		
		if (isset($_SESSION['thorax']['flash'][$this->uid]['input'])) {
			$this->data = $_SESSION['thorax']['flash'][$this->uid]['input'];
		}
		
		if (isset($_POST['thorax']['uid']) && $_POST['thorax']['uid'] == $this->uid) {
			unset($_POST['thorax']);
			
			$this->data = array_merge_recursive_distinct($this->data, $_POST);
			
			$this->is_submitted = true;
			
			$_SESSION['thorax']['flash'][$this->uid]['input'] = $this->data;
		} else {
			unset($_SESSION['thorax']['flash']);
		}
	}
	
	public function input ($name, array $attributes = null, array $parameters = null) {
		if (!isset($this->input_index[$name])) {
			$this->input_index[$name] = [];
		}
		
		$input = new form\Input($this, $name, $attributes, $parameters, count($this->input_index[$name]));
		
		$this->input_index[$name][] = $input;
		
		return $input;
	}
	
	public function is ($event) {
		if ($event === 'submitted') {
			return $this->is_submitted;
		}
	}
	
	public function getData () {
		return $this->data;
	}
	
	public function getUid () {
		return $this->uid;
	}
	
	public function getUidIndex () {
		return $this->uid_index++;
	}
	
	public function clearFlash () {
		unset($_SESSION['thorax']['flash'][$this->uid]['input']);
	}
}

/**
 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
 * keys to arrays rather than overwriting the value in the first array with the duplicate
 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
 * this happens (documented behavior):
 *
 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('org value', 'new value'));
 *
 * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
 * Matching keys' values in the second array overwrite those in the first array, as is the
 * case with array_merge, i.e.:
 *
 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('new value'));
 *
 * Parameters are passed by reference, though only for performance reasons. They're not
 * altered by this function.
 *
 * @param array $array1
 * @param array $array2
 * @return array
 * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
 * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
 */
function array_merge_recursive_distinct ( array &$array1, array &$array2 )	{
	$merged = $array1;
	
	foreach ( $array2 as $key => &$value ) {
		if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) ) {
			$merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
		} else {
			$merged [$key] = $value;
		}
	}
	
	return $merged;
}