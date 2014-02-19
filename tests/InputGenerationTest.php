<?php
class InputGenerationTest extends PHPUnit_Framework_TestCase {
	/**
	 * @expectedException RuntimeException
	 */
	public function testCovertInputInstanceToStringTwoTimes () {
		$input = new \Gajus\Dora\Input('test');

		$input->toString();
		$input->toString();
	}

	public function testTypeText () {
		$input = new \Gajus\Dora\Input('test');

		$this->assertSame('<input name="test" type="text" value="">', $input->toString());
	}

	public function testTypeTextarea () {
		$input = new \Gajus\Dora\Input('test', ['type' => 'textarea']);

		$this->assertSame('<textarea name="test"></textarea>', $input->toString());
	}

	/**
	 * @dataProvider testTypeSelectProvider
	 */
	public function testTypeSelect ($attributes, $properties) {
		$input = new \Gajus\Dora\Input('test', $attributes, $properties);

		$this->assertSame('<select name="test"></select>', $input->toString());
	}

	public function testTypeSelectProvider () {
		return [
			[['type' => 'select'], []],
			[null, ['options' => []]]
		];
	}

	public function testTypePassword () {
		$input = new \Gajus\Dora\Input('test', ['type' => 'password']);

		$this->assertSame('<input name="test" type="password">', $input->toString());
	}

	public function testTypeCheckboxWithValue () {
		$input = new \Gajus\Dora\Input('test', ['type' => 'checkbox', 'value' => '1']);

		$this->assertSame('<input name="test" type="checkbox">', $input->toString());
	}

	/**
	 * @expectedException BadMethodCallException
	 */
	public function testTypeCheckboxWithoutValue () {
		$input = new \Gajus\Dora\Input('test', ['type' => 'checkbox']);

		$input->toString();
	}

	public function testTypeRadioWithValue () {
		$input = new \Gajus\Dora\Input('test', ['type' => 'radio', 'value' => '1']);

		$this->assertSame('<input name="test" type="radio">', $input->toString());
	}

	/**
	 * @expectedException BadMethodCallException
	 */
	public function testTypeRadioWithoutValue () {
		$input = new \Gajus\Dora\Input('test', ['type' => 'radio']);

		$input->toString();
	}

	public function testCustomAttributeOutput () {
		$input = new \Gajus\Dora\Input('test', ['data-test' => 'foo']);

		$this->assertSame('<input data-test="foo" name="test" type="text" value="">', $input->toString());
	}
}