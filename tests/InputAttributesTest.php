<?php
class InputAttributesTest extends PHPUnit_Framework_TestCase {
	public function testGetUndefinedAttribute () {
		$input = new \gajus\dora\Input('test');

		$this->assertNull($input->getAttribute('data-foo'));
	}

	public function testGetUndefinedIdAttribute () {
		$input = new \gajus\dora\Input('test');

		$this->assertNotNull($input->getAttribute('id'));
	}

	public function testGetDefinedIdAttribute () {
		$input = new \gajus\dora\Input('test', ['id' => 'test']);

		$this->assertSame('test', $input->getAttribute('id'));
	}

	/**
	 * @expectedException LogicException
	 */
	public function testGetUndefinedIdAttributeAfterStringification () {
		$input = new \gajus\dora\Input('test');

		(string) $input;

		$input->getAttribute('id');
	}

	public function testSetInputAttribute () {
		$input = new \gajus\dora\Input('test', ['data-foo' => 'bar']);

		$this->assertSame('bar', $input->getAttribute('data-foo'));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetNameAttribute () {
		return $this->markTestSkipped('setAttribute is private method');

		$input = new \gajus\dora\Input('test');

		$input->setAttribute('name', 'test');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetAttributeNameNotString () {
		return $this->markTestSkipped('setAttribute is private method');

		$input = new \gajus\dora\Input('test');

		$input->setAttribute(['?'], 'test');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetAttributeValueNotString () {
		return $this->markTestSkipped('setAttribute is private method');

		$input = new \gajus\dora\Input('test');

		$input->setAttribute('test', ['?']);
	}

	/**
	 * @expectedException LogicException
	 */
	public function testSetAttributeValueAfterStringification () {
		return $this->markTestSkipped('setAttribute is private method');

		$input = new \gajus\dora\Input('test');

		(string) $input;

		$input->setAttribute('test', 'test');
	}
}