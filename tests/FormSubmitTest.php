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

    public function testIsSubmittedInputWithoutCSFRCheck () {
        $uid = (string) crc32(__FILE__ . '_' . (__LINE__ + 4));

        $input['gajus']['dora']['uid'] = $uid;

        $form = new \Gajus\Dora\Form($input);

        $this->assertSame($form->getUid(), $uid);

        $this->assertFalse($form->isSubmitted());
        $this->assertTrue($form->isSubmitted(false));
    }

    public function testIsSubmittedInputWithCSFRCheck () {
        $uid = (string) crc32(__FILE__ . '_' . (__LINE__ + 5));
        $csfr = sha1(session_id());

        $input['gajus']['dora']['uid'] = $uid;
        $input['gajus']['dora']['csfr'] = $uid;

        $form = new \Gajus\Dora\Form($input);

        $this->assertSame($form->getUid(), $uid);

        $this->assertTrue($form->isSubmitted());
        $this->assertTrue($form->isSubmitted());
    }
}