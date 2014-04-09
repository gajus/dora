<?php
class FormGenerationTest extends PHPUnit_Framework_TestCase {
	public function testCreateInput () {
		$form = new \Gajus\Dora\Form();

		$input = $form->input('name');

		$this->assertInstanceOf('gajus\dora\input', $input);
	}

	/**
	 * @expectedException Gajus\Dora\Exception\InvalidArgumentException
	 * @expectedExceptionMessage Input instantiated using Form::input() method cannot explicitly define "value" property.
	 */
	public function testFormInputCannotSetValueProperty () {
		$form = new \Gajus\Dora\Form();

		$form->input('test', null, ['value' => 'test']);
	}
}