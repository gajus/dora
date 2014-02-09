<?php
namespace gajus\dora;

class Dress {
	private
		$form,
		$dress;

	public function __construct (Form $form, $dress = 'gajus\dora\dress\Dora') {
		$this->form = $form;
		$this->dress = $dress;

		if (!is_subclass_of($dress, 'gajus\dora\dress\Manikin')) {
			throw new \InvalidArgumentException('Dress class must extend gajus\dora\dress\Manikin.');
		}
	}

	public function input ($name, array $attributes = null, array $properties = []) {
		return new $this->dress($this->form->input($name, $attributes, $properties), $this->form);
	}
}