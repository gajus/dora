<?php
namespace Gajus\Dora;

/**
 * @link https://github.com/gajus/dora for the canonical source repository
 * @license https://github.com/gajus/dora/blob/master/LICENSE BSD 3-Clause
 */
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

	public function getForm () {
		return $this->form;
	}
}