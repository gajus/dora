<?php
class FormGeneration extends PHPUnit_Framework_TestCase {
	public function testCreateInput () {
		$form = new \gajus\dora\Form();

		$this->assertInstanceOf($form->input('name'), 'gajus\dora\input');
	}

	public function testDefaultData () {
		$form = new \gajus\dora\Form(['foo' => 'bar']);

		$data = $form->getData();

		$this->assertSame($data, ['foo' => 'bar']);
	}

	public function testInputData () {
		$form = new \gajus\dora\Form(null, ['foo' => 'bar']);

		$data = $form->getData();

		$this->assertSame($data, ['foo' => 'bar']);
	}

	public function testDefaultInputData () {
		$_POST = ['foo' => 'bar'];

		$form = new \gajus\dora\Form();

		$data = $form->getData();

		$this->assertSame($data, ['foo' => 'bar']);
	}

	public function testInputDataOverwritesDefaultData () {
		$form => new \gajus\dora\Form(['foo' => 'bar', 'baz' => 'qux'], ['baz' => 'quux']);

		$data = $form->getData();

		$this->assertSame($data, ['baz' => 'quux']);
	}
}