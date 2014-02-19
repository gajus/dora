<?php
class DoraDressTest extends PHPUnit_Framework_TestCase {
	private
		$form;

	public function setUp () {
		$this->form = new \Gajus\Dora\Form();
		$this->dress = new \Gajus\Dora\Dress($this->form);
	}

	public function testSimpleOutput () {
		$dressed_input = $this->dress->input('foo', ['id' => 'bar']);

		$this->assertSame('<div class="dora-input"><label for="bar">Foo</label><div class="input"><input id="bar" name="foo" type="text" value=""></div></div>', $this->removeWhitespace($dressed_input->toString()));
	}

	public function testOutputWithDescription () {
		$dressed_input = $this->dress->input('foo', ['id' => 'bar'], ['description' => 'test']);

		$this->assertSame('<div class="dora-input"><label for="bar">Foo</label><div class="input"><input id="bar" name="foo" type="text" value=""></div><div class="description"><p>test</p></div></div>', $this->removeWhitespace($dressed_input->toString()));
	}

	private function removeWhitespace ($html) {
		return trim(preg_replace('~>\s+<~', '><', $html));
	}
}