<?php
class FormGenerationTest extends PHPUnit_Framework_TestCase {
	public function testCreateInput () {
		$form = new \Gajus\Dora\Form();

		$input = $form->input('name');

		$this->assertInstanceOf('gajus\dora\input', $input);
	}

	/**
	 * @expectedException Gajus\Dora\Exception\InvalidArgumentException
	 * @expectedExceptionMessage Input instantiated using Form::input() method cannot explicitly define "value" property.
	 */
	public function testFormInputCannotSetValueProperty () {
		$form = new \Gajus\Dora\Form();

		$form->input('test', null, ['value' => 'test']);
	}

	public function testFormSigning () {
		$uid = (string) crc32(__FILE__ . '_' . (__LINE__ + 3));
        $csrf = sha1(session_id());

		$form = new \Gajus\Dora\Form();

		$this->assertSame($form->getUid(), $uid);

		$this->assertSame(preg_replace('/[^a-b]/', '', '<input type="hidden" name="gajus[dora][uid]" value="' . $uid . '"><input type="hidden" name="gajus[dora][csrf]" value="' . $csrf . '">'), preg_replace('/[^a-b]/', '', $form->sign()));
	}
}