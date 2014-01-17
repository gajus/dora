<?php
class InputPropertyTest extends PHPUnit_Framework_TestCase {
	public function testSetInputPropertyName () {
		$input = new \gajus\dora\Input('test', null, ['name' => 'bar']);

		$this->assertSame('bar', $input->getProperty('name'));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetInputPropertyUnknown () {
		new \gajus\dora\Input('test', null, ['foo' => 'bar']);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetTextInputPropertyUnsupported () {
		new \gajus\dora\Input('test', ['type' => 'text'], ['options' => ['a', 'b']]);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetPropertyNameNotString () {
		$input = new \gajus\dora\Input('test');

		$input->setProperty(['?'], 'test');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testGetUndefinedProperty () {
		$input = new \gajus\dora\Input('test');

		$this->assertSame('bar', $input->getProperty('foo'));
	}
}