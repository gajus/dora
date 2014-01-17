<?php
class InputValueGenerationTest extends PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider testTextValueProvider
	 */
	public function testTextValue ($input, $expected) {
		$input = new \gajus\dora\Input('test', null, ['value' => $input]);

		$this->assertSame($expected, $input->toString());
	}

	public function testTextValueProvider () {
		return [
			['test', '<input name="test" type="text" value="test">'],
			['<test/>"', '<input name="test" type="text" value="&#60;test/&#62;&#34;">']
		];
	}

	/**
	 * @dataProvider testTextareaValueProvider
	 */
	public function testTextareaValue ($input, $expected) {
		$input = new \gajus\dora\Input('test', ['type' => 'textarea'], ['value' => $input]);

		$this->assertSame($expected, $input->toString());
	}

	public function testTextareaValueProvider () {
		return [
			['test', '<textarea name="test">test</textarea>'],
			['<test/>"', '<textarea name="test">&#60;test/&#62;&#34;</textarea>']
		];
	}

	public function testPasswordValue () {
		$input = new \gajus\dora\Input('test', ['type' => 'password'], ['value' => 'test']);

		$this->assertSame('<input name="test" type="password">', $input->toString());
	}

	public function testSelectValue () {
		$input = new \gajus\dora\Input('test', null, ['options' => ['a', 'b', 'c'], 'value' => 1]);

		$this->assertSame('<select name="test"><option value="0">a</option><option value="1" selected="selected">b</option><option value="2">c</option></select>', $input->toString());
	}

	public function testMultipleSelectValue () {
		// @todo This should not work
		// $input = new \gajus\dora\Input('test[]', ['multiple' => 'multiple'], ['options' => ['a', 'b', 'c'], 'value' => 1]);
		// move input validation for array input logic from Form to Input

		$input = new \gajus\dora\Input('test[]', ['multiple' => 'multiple'], ['options' => ['a', 'b', 'c'], 'value' => [1, 2]]);

		$this->assertSame('<select multiple="multiple" name="test[]"><option value="0">a</option><option value="1" selected="selected">b</option><option value="2" selected="selected">c</option></select>', $input->toString());
	}
}