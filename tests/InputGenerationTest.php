<?php
class InputGenerationTest extends PHPUnit_Framework_TestCase {
	public function testTypeText () {
		$input = new \gajus\dora\Input('test');

		$this->assertSame($input->toString(), '<input name="test" type="text" value="">');
	}

	public function testTypeTextarea () {
		$input = new \gajus\dora\Input('test', ['type' => 'textarea']);

		$this->assertSame($input->toString(), '<textarea name="test"></textarea>');
	}

	/**
	 * @dataProvider testTypeSelectProvider
	 */
	public function testTypeSelect ($attributes, $properties) {
		$input = new \gajus\dora\Input('test', $attributes, $properties);

		$this->assertSame($input->toString(), '<select name="test" type="select"></select>');
	}

	public function testTypeSelectProvider () {
		return [
			[['type' => 'select'], []],
			[null, ['options' => []]]
		];
	}

	public function testTypePassword () {
		$input = new \gajus\dora\Input('test', ['type' => 'password']);

		$this->assertSame($input->toString(), '<input name="test" type="password">');
	}
}