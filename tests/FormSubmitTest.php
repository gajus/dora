<?php
class FormSubmitTest extends PHPUnit_Framework_TestCase {
    public function testIsNotSubmittedWhenSessionIsPresent () {
        $uid = (string) crc32(__FILE__ . '_' . (__LINE__ + 4));

        $_SESSION['gajus']['dora']['flash'][$uid] = [];

        $form = new \Gajus\Dora\Form();

        $this->assertSame($form->getUid(), $uid);

        $this->assertFalse($form->isSubmitted(false));
    }

    public function testIsNotSubmittedWhenDifferentUid () {
        $input['gajus']['dora']['uid'] = 'test';

        $form = new \Gajus\Dora\Form($input);

        $this->assertFalse($form->isSubmitted(false));
    }

    public function testIsSubmittedInputWithoutCSRFCheck () {
        $uid = (string) crc32(__FILE__ . '_' . (__LINE__ + 4));

        $input['gajus']['dora']['uid'] = $uid;

        $form = new \Gajus\Dora\Form($input);

        $this->assertSame($form->getUid(), $uid);

        $this->assertFalse($form->isSubmitted());
        $this->assertTrue($form->isSubmitted(false));
    }

    public function testIsSubmittedInputWithCSRFCheck () {
        $uid = (string) crc32(__FILE__ . '_' . (__LINE__ + 6));
        $csrf = sha1(session_id());

        $input['gajus']['dora']['uid'] = $uid;
        $input['gajus']['dora']['csrf'] = $csrf;

        $form = new \Gajus\Dora\Form($input);

        $this->assertSame($form->getUid(), $uid);

        $this->assertTrue($form->isSubmitted(false));
        $this->assertTrue($form->isSubmitted());
    }
}