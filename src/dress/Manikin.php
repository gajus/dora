<?php
namespace gajus\dora\dress;

abstract class Manikin {

	private
		$input,
		$form;
	
	final public function __construct (\gajus\dora\Input $input, \gajus\dora\Form $form) {
		$this->input = $input;
		$this->form = $form;
	}
	
	public function getInput () {
		return $this->input;
	}

	public function getForm () {
		return $this->form;
	}

	abstract public function toString ();
	
	public function __toString () {
		return $this->toString();
	}
}