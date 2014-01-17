<?php
class FormGenerationTest extends PHPUnit_Framework_TestCase {
	public function testCreateInput () {
		$form = new \gajus\dora\Form();

		$input = $form->input('name');

		$this->assertInstanceOf('gajus\dora\input', $input);
	}

	public function testDefaultData () {
		$form = new \gajus\dora\Form(['foo' => 'bar']);

		$data = $form->getData();

		$this->assertSame(['foo' => 'bar'], $data);
	}

	public function testInputData () {
		$form = new \gajus\dora\Form(null, ['foo' => 'bar']);

		$data = $form->getData();

		$this->assertSame(['foo' => 'bar'], $data);
	}

	public function testDefaultInputData () {
		$_POST = ['foo' => 'bar'];

		$form = new \gajus\dora\Form();

		$data = $form->getData();

		$this->assertSame(['foo' => 'bar'], $data);
	}

	public function testInputDataOverwritesDefaultData () {
		$form = new \gajus\dora\Form(['foo' => 'bar', 'baz' => 'qux'], ['baz' => 'quux']);

		$data = $form->getData();

		$this->assertSame(['baz' => 'quux'], $data);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFormInputCannotSetValueProperty () {
		$form = new \gajus\dora\Form();
		$form->input('test', null, ['value' => 'test']);
	}
}