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
	public function testValue ($name, $value) {
		$input = $this->form->input($name);

		$this->assertSame($input->getValue(), $value);
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

		$this->assertSame($input1->getValue(), 5);
		$this->assertSame($input2->getValue(), 10);
		
		$this->assertNull($input3->getValue());
	}
}