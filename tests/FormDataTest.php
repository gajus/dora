<?php
class FormDataTest extends PHPUnit_Framework_TestCase {
    public function testSessionData () {
        $uid = (string) crc32(__FILE__ . '_' . (__LINE__ + 4));

        $_SESSION['gajus']['dora']['flash'][$uid] = ['foo' => 'bar'];

        $form = new \Gajus\Dora\Form();

        $this->assertSame($form->getUid(), $uid);

        $this->assertFalse($form->isSubmitted(false));

        $this->assertSame(['foo' => 'bar'], $form->getData());
    }

    public function testInputData () {
        $uid = (string) crc32(__FILE__ . '_' . (__LINE__ + 11));

        $input = [
            'foo' => 'bar',
            'gajus' => [
                'dora' => [
                    'uid' => $uid
                ]
            ]
        ];

        $form = new \Gajus\Dora\Form($input);

        $this->assertSame($form->getUid(), $uid);

        $this->assertTrue($form->isSubmitted(false));

        $this->assertSame(['foo' => 'bar'], $form->getData());
    }
}