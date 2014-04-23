<?php
class InputValueGenerationTest extends PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider testTextValueProvider
	 */
	public function testTextValue ($input, $expected) {
		$input = new \Gajus\Dora\Input('test', null, ['value' => $input]);

		$this->assertSame($expected, $input->toString());
	}

	public function testTextValueProvider () {
		return [
			['test', '<input name="test" type="text" value="test">'],
			['<test/>"', '<input name="test" type="text" value="&#60;test/&#62;&#34;">']
		];
	}

	public function testTextWithDefaultValue () {
		$input = new \Gajus\Dora\Input('test', ['value' => 'test']);

		$this->assertSame('<input name="test" type="text" value="test">', $input->toString());
	}

	public function testTextWithDefaultValueOverwritten () {
		$input = new \Gajus\Dora\Input('test', ['value' => 'a'], ['value' => 'b']);

		$this->assertSame('<input name="test" type="text" value="b">', $input->toString());
	}

	/**
	 * @dataProvider testTextareaValueProvider
	 */
	public function testTextareaValue ($input, $expected) {
		$input = new \Gajus\Dora\Input('test', ['type' => 'textarea'], ['value' => $input]);

		$this->assertSame($expected, $input->toString());
	}

	public function testTextareaValueProvider () {
		return [
			['test', '<textarea name="test">test</textarea>'],
			['<test/>"', '<textarea name="test">&#60;test/&#62;&#34;</textarea>']
		];
	}

	public function testPasswordValue () {
		$input = new \Gajus\Dora\Input('test', ['type' => 'password'], ['value' => 'test']);

		$this->assertSame('<input name="test" type="password">', $input->toString());
	}

	public function testSelectValue () {
		$input = new \Gajus\Dora\Input('test', null, ['options' => [1 => 'a', 'b', 'c'], 'value' => 1]);

		$this->assertSame('<select name="test"><option value="1" selected="selected">a</option><option value="2">b</option><option value="3">c</option></select>', $input->toString());
	}

	public function testSelectValuePlaceholder () {
		$input = new \Gajus\Dora\Input('test', null, ['options' => ['placeholder', 'a', 'b', 'c'], 'value' => 2]);

		$this->assertSame('<select name="test"><option disabled="disabled">placeholder</option><option value="1">a</option><option value="2" selected="selected">b</option><option value="3">c</option></select>', $input->toString());
	}

	public function testSelectValuePlaceholderSelected () {
		$input = new \Gajus\Dora\Input('test', null, ['options' => ['placeholder', 'a', 'b', 'c']]);

		$this->assertSame('<select name="test"><option selected="selected" disabled="disabled">placeholder</option><option value="1">a</option><option value="2">b</option><option value="3">c</option></select>', $input->toString());
	}

	public function testMultipleSelectValue () {
		// @todo This should not work
		// $input = new \gajus\dora\Input('test[]', ['multiple' => 'multiple'], ['options' => ['a', 'b', 'c'], 'value' => 1]);
		// move input validation for array input logic from Form to Input

		$input = new \Gajus\Dora\Input('test[]', ['multiple' => 'multiple'], ['options' => [1 => 'a', 'b', 'c'], 'value' => [2, 3]]);

		$this->assertSame('<select multiple="multiple" name="test[]"><option value="1">a</option><option value="2" selected="selected">b</option><option value="3" selected="selected">c</option></select>', $input->toString());
	}

	public function testTypeCheckboxWithValue () {
		$input = new \Gajus\Dora\Input('test', ['type' => 'checkbox', 'value' => '2'], ['value' => 2]);

		$this->assertSame('<input checked="checked" name="test" type="checkbox" value="2">', $input->toString());
	}

	public function testTypeCheckboxWithDifferentValue () {
		$input = new \Gajus\Dora\Input('test', ['type' => 'checkbox', 'value' => '2'], ['value' => 3]);

		$this->assertSame('<input name="test" type="checkbox" value="2">', $input->toString());
	}

	public function testTypeRadioWithValue () {
		$input = new \Gajus\Dora\Input('test', ['type' => 'radio', 'value' => '2'], ['value' => 2]);

		$this->assertSame('<input checked="checked" name="test" type="radio" value="2">', $input->toString());
	}

	public function testTypeRadioWithDifferentValue () {
		$input = new \Gajus\Dora\Input('test', ['type' => 'radio', 'value' => '2'], ['value' => 3]);

		$this->assertSame('<input name="test" type="radio" value="2">', $input->toString());
	}
}