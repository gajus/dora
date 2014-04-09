<?php
class FormSubmitTest extends PHPUnit_Framework_TestCase {
	/*

    public function testIsNotSubmitted () {
        $form = new \Gajus\Dora\Form();

        $this->assertFalse($form->isSubmitted());
    }*/

    public function testIsNotSubmittedWhenSessionIsPresent () {
        $uid = (string) crc32(__FILE__ . '_' . (__LINE__ + 4));

        $_SESSION['gajus']['dora']['flash'][$uid] = [];

        $form = new \Gajus\Dora\Form();

        $this->assertSame($form->getUid(), $uid);

        $this->assertFalse($form->isSubmitted());
    }

    public function testIsNotSubmittedWhenDifferentUid () {
        $input['gajus']['dora']['uid'] = 'test';

        $form = new \Gajus\Dora\Form($input);

        $this->assertFalse($form->isSubmitted());
    }

    public function testIsSubmittedInput () {
        $uid = (string) crc32(__FILE__ . '_' . (__LINE__ + 4));

        $input['gajus']['dora']['uid'] = $uid;

        $form = new \Gajus\Dora\Form($input);

        $this->assertSame($form->getUid(), $uid);

        $this->assertTrue($form->isSubmitted());
    }
}