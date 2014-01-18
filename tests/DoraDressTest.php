<?php
class DoraDressTest extends PHPUnit_Framework_TestCase {
	private
		$form;

	public function setUp () {
		$this->form = new \gajus\dora\Form();
		$this->dress = new \gajus\dora\Dress($this->form);
	}

	public function testSimpleOutput () {
		$dressed_input = $this->dress->input('foo', ['id' => 'bar']);

		$this->assertSame('<div class="dora-input"><label for="bar">Foo</label><input id="bar" name="foo" type="text" value=""></div>', $this->removeWhitespace($dressed_input->toString()));
	}

	private function removeWhitespace ($html) {
		return trim(preg_replace('~>\s+<~', '><', $html));
	}
}