<?php
class NoneTest extends PHPUnit_Framework_TestCase {
	public function testForm () {
		$form = new \Gajus\Dora\Form([], null);

		$input = $form->input('foo');

		$input = '' . $input;

		$this->assertSame('<input name="foo" type="text" value="">', $input);
	}

	public function testInput () {
		$input = new \Gajus\Dora\Input('foo', null, null, null);

		$input = '' . $input;

		$this->assertSame('<input name="foo" type="text" value="">', $input);
	}
}