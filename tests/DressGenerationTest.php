<?php
class DressGenerationTest extends PHPUnit_Framework_TestCase {
	private
		$form;

	public function setUp () {
		$this->form = new \Gajus\Dora\Form();
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testCreateDressThatDoesNotExtendManikin () {
		new \Gajus\Dora\Dress($this->form, 'stdClass');
	}

	public function testCreateInputUsingDefaultDress () {
		$dress = new \Gajus\Dora\Dress($this->form);

		$this->assertInstanceOf('Gajus\Dora\Dress\Dora', $dress->input('test'));
	}

	public function testCreateInputUsingTestDress () {
		$dress = new \Gajus\Dora\Dress($this->form, 'Gajus\Dora\Dress\Test');

		$this->assertInstanceOf('Gajus\Dora\Dress\Test', $dress->input('test'));
	}

	public function testCovertDressedInputToString () {
		$dress = new \Gajus\Dora\Dress($this->form, 'Gajus\Dora\Dress\Test');

		$dressed_input = $dress->input('test');

		$input = $dressed_input->getInput();

		$this->assertSame($input->getAttribute('id'), $dressed_input->toString());
	}

	public function testCastDressedInputToString () {
		$dress = new \Gajus\Dora\Dress($this->form, 'Gajus\Dora\Dress\Test');

		$dressed_input = $dress->input('test');

		$input = $dressed_input->getInput();

		$this->assertSame($input->getAttribute('id'), (string) $dressed_input);
	}

	public function testGetFormInstanceFromDressedInputInstance () {
		$dress = new \Gajus\Dora\Dress($this->form);

		$dressed_input = $dress->input('test');

		$this->assertSame($this->form, $dressed_input->getForm());
	}

	public function testGetInputInstanceFromDressedInputInstance () {
		$dress = new \Gajus\Dora\Dress($this->form);

		$dressed_input = $dress->input('test');

		$this->assertInstanceOf('Gajus\Dora\Input', $dressed_input->getInput());
	}
}