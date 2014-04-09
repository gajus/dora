<?php
class TestTest extends PHPUnit_Framework_TestCase {
	public function testForm () {
		$form = new \Gajus\Dora\Form([], 'Gajus\Dora\Template\Test');

		$input = $form->input('foo');

		$input = '' . $input;

		$this->assertSame('test', $input);
	}

	public function testInput () {
		$input = new \Gajus\Dora\Input('foo', null, null, 'Gajus\Dora\Template\Test');
		$input = '' . $input;

		$this->assertSame('test', $input);
	}
}