<?php
class FormGenerationTest extends PHPUnit_Framework_TestCase {
	public function testCreateInput () {
		$form = new \Gajus\Dora\Form();

		$input = $form->input('name');

		$this->assertInstanceOf('gajus\dora\input', $input);
	}

	/*public function testDefaultData () {
		$form = new \Gajus\Dora\Form(['foo' => 'bar']);

		$data = $form->getData();

		$this->assertSame(['foo' => 'bar'], $data);
	}

	public function testInputDataPostDirect () {
		$_POST = ['foo' => 'bar'];

		$form = new \Gajus\Dora\Form();

		$this->assertFalse($form->isSubmitted());

		$data = $form->getData();

		$this->assertSame([], $data);
	}

	public function testInputDataPostSession () {
		$_SESSION['gajus']['dora']['flash'] = [
			'foo' => 'bar',
			'gajus' => [
				'dora' => [
					'uid' => (string) crc32(__FILE__ . '_' . (__LINE__ + 5))
				]
			]
		];

		$form = new \Gajus\Dora\Form();

		$this->assertTrue($form->isSubmitted());

		$data = $form->getData();

		$this->assertSame(['foo' => 'bar'], $data);
	}
	
	public function testInputDataOverwritesDefaultData () {
		$_SESSION['gajus']['dora']['flash'] = [
			'foo' => 'bar',
			'gajus' => [
				'dora' => [
					'uid' => (string) crc32(__FILE__ . '_' . (__LINE__ + 5))
				]
			]
		];

		$form = new \Gajus\Dora\Form(['foo' => 'bar', 'baz' => 'qux']);

		$data = $form->getData();

		$this->assertSame(['foo' => 'bar'], $data);
	}*/

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFormInputCannotSetValueProperty () {
		$form = new \Gajus\Dora\Form();

		$form->input('test', null, ['value' => 'test']);
	}
}