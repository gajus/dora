<?php
class InputGenerationTest extends PHPUnit_Framework_TestCase {
	public function testTypeText () {
		$input = new \gajus\dora\Input('test');

		$this->assertSame('<input name="test" type="text" value="">', $input->toString());
	}

	public function testTypeTextarea () {
		$input = new \gajus\dora\Input('test', ['type' => 'textarea']);

		$this->assertSame('<textarea name="test"></textarea>', $input->toString());
	}

	/**
	 * @dataProvider testTypeSelectProvider
	 */
	public function testTypeSelect ($attributes, $properties) {
		$input = new \gajus\dora\Input('test', $attributes, $properties);

		$this->assertSame('<select name="test"></select>', $input->toString());
	}

	public function testTypeSelectProvider () {
		return [
			[['type' => 'select'], []],
			[null, ['options' => []]]
		];
	}

	public function testTypePassword () {
		$input = new \gajus\dora\Input('test', ['type' => 'password']);

		$this->assertSame('<input name="test" type="password">', $input->toString());
	}

	public function testCustomAttributeValue () {
		$input = new \gajus\dora\Input('test', ['data-test' => 'foo']);

		$this->assertSame('foo', $input->getAttribute('data-test'));
	}

	public function testCustomAttributeOutput () {
		$input = new \gajus\dora\Input('test', ['data-test' => 'foo']);

		$this->assertSame('<input data-test="foo" name="test" type="text" value="">', $input->toString());
	}
}