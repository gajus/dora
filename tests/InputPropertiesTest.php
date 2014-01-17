<?php
class InputPropertyTest extends PHPUnit_Framework_TestCase {
	public function testSetInputProperty () {
		$input = new \gajus\dora\Input('test', null, ['foo' => 'bar']);

		$this->assertSame('bar', $input->getProperty('foo'));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testGetUndefinedProperty () {
		$input = new \gajus\dora\Input('test');

		$this->assertSame('bar', $input->getProperty('foo'));
	}
}