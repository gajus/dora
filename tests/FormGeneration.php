<?php
class FormGeneration extends PHPUnit_Framework_TestCase {
	public function testCreateInput () {
		$form = new \gajus\dora\Form();

		$this->assertInstanceOf($form->input('name'), 'gajus\dora\input');
	}
}