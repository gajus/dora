<?php
class InputPropertyTest extends PHPUnit_Framework_TestCase {
	public function testSetInputPropertyName () {
		$input = new \Gajus\Dora\Input('test', null, ['name' => 'bar']);

		$this->assertSame('bar', $input->getProperty('name'));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetTextInputPropertyUnsupported () {
		new \Gajus\Dora\Input('test', ['type' => 'text'], ['options' => ['a', 'b']]);
	}
}