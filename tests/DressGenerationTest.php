<?php
class DressGenerationTest extends PHPUnit_Framework_TestCase {
	private
		$form;

	public function setUp () {
		$this->form = new \gajus\dora\Form();
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testCreateDressThatDoesNotExtendManikin () {
		new \gajus\dora\Dress($this->form, 'stdClass');
	}

	public function testCreateInputUsingDefaultDress () {
		$dress = new \gajus\dora\Dress($this->form);

		$this->assertInstanceOf('gajus\dora\dress\Dora', $dress->input('test'));
	}

	public function testCreateInputUsingTestDress () {
		$dress = new \gajus\dora\Dress($this->form, 'gajus\dora\dress\Test');

		$this->assertInstanceOf('gajus\dora\dress\Test', $dress->input('test'));
	}

	public function testCovertDressedInputToString () {
		$dress = new \gajus\dora\Dress($this->form, 'gajus\dora\dress\Test');

		$dressed_input = $dress->input('test');

		$input = $dressed_input->getInput();

		$this->assertSame($input->getAttribute('id'), $dressed_input->toString());
	}

	public function testGetFormInstanceFromDressedInputInstance () {
		$dress = new \gajus\dora\Dress($this->form);

		$dressed_input = $dress->input('test');

		$this->assertSame($this->form, $dressed_input->getForm());
	}

	public function testGetInputInstanceFromDressedInputInstance () {
		$dress = new \gajus\dora\Dress($this->form);

		$dressed_input = $dress->input('test');

		$this->assertInstanceOf('gajus\dora\Input', $dressed_input->getInput());
	}
}