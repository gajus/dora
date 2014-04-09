<?php
class TraditionalTest extends PHPUnit_Framework_TestCase {
	public function testDefault () {
		$form = new \Gajus\Dora\Form([], 'Gajus\Dora\Template\Traditional');

		$input = $form->input('foo', ['id' => 'test']);

		$input = $input->toString();

		// https://gist.github.com/anonymous/220b2cec6d51ea5bc728

		$this->assertSame(preg_replace('/[^a-z]/', '', '
			<div class="dora-input">
				<label for="test">Foo</label>
				<input id="test" name="foo" type="text" value="">
			</div>
			'), preg_replace('/[^a-z]/', '',$input));
	}
}