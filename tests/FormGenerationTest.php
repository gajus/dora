<?php
class FormGenerationTest extends PHPUnit_Framework_TestCase {
	public function testCreateInput () {
		$form = new \Gajus\Dora\Form();

		$input = $form->input('name');

		$this->assertInstanceOf('gajus\dora\input', $input);
	}

	public function testDefaultData () {
		$form = new \Gajus\Dora\Form(['foo' => 'bar']);

		$data = $form->getData();

		$this->assertSame(['foo' => 'bar'], $data);
	}

	public function testInputData () {
		$form = new \Gajus\Dora\Form(null, ['foo' => 'bar']);

		$data = $form->getData();

		$this->assertSame(['foo' => 'bar'], $data);
	}

	public function testDefaultInputData () {
		$_POST = ['foo' => 'bar'];

		$form = new \Gajus\Dora\Form();

		$data = $form->getData();

		$this->assertSame(['foo' => 'bar'], $data);
	}

	public function testInputDataOverwritesDefaultData () {
		$form = new \Gajus\Dora\Form(['foo' => 'bar', 'baz' => 'qux'], ['baz' => 'quux']);

		$data = $form->getData();

		$this->assertSame(['baz' => 'quux'], $data);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFormInputCannotSetValueProperty () {
		$form = new \Gajus\Dora\Form();

		$form->input('test', null, ['value' => 'test']);
	}
}