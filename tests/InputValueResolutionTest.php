<?php
class InputValueResolutionTest extends PHPUnit_Framework_TestCase {
	
	private
		$form;

	public function setUp () {
		$this->form = new \gajus\dora\Form([
			'bar' => [5, 10],
			'baz' => [
				null,
				['c', 'd']
			],
			'qux' => [
				'ten' => 10
			],
			'quux' => [
				'a' => [
					'b' => 'c'
				]
			]
		]);
	}

	/**
	 * @dataProvider valueNumericArrayProvider
	 * @dataProvider valueAssociativeArrayProvider
	 */
	public function testValue ($name, $expected) {
		$input = $this->form->input($name);

		$this->assertSame($expected, $input->getValue());
	}

	public function valueNumericArrayProvider () {
		return [
			['bar[1]', 10],
			['baz[1][1]', 'd']
		];
	}

	public function valueAssociativeArrayProvider () {
		return [
			['qux[ten]', 10],
			['quux[a][b]', 'c']
		];
	}

	/**
	 * @dataProvider noValueProvider
	 */
	public function testNoValue ($name) {
		$input = $this->form->input($name);

		$this->assertNull($input->getValue());
	}

	public function noValueProvider () {
		return [
			['bar[foo]'],
			['bar[2]'],
			['bar'] // "bar" should not resolve ['a', 'b'], because input declaration is seeking for scalar value.
		];
	}

	public function testIndex () {
		$input1 = $this->form->input('bar[]');
		$input2 = $this->form->input('bar[]');
		$input3 = $this->form->input('bar[]');

		$this->assertSame(5, $input1->getValue());
		$this->assertSame(10, $input2->getValue());
		
		$this->assertNull($input3->getValue());
	}
}