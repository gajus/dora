<?php
namespace Gajus\Dora;

class Dress {
	private
		$form,
		$dress;

	public function __construct (Form $form, $dress = 'Gajus\Dora\Dress\Dora') {
		$this->form = $form;
		$this->dress = $dress;

		if (!is_subclass_of($dress, 'Gajus\Dora\Dress\Manikin')) {
			throw new \InvalidArgumentException('Dress class must extend Gajus\Dora\Dress\Manikin.');
		}
	}

	public function input ($name, array $attributes = null, array $properties = []) {
		return new $this->dress($this->form->input($name, $attributes, $properties), $this->form);
	}
}