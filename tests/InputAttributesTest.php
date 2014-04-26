<?php
class InputAttributesTest extends PHPUnit_Framework_TestCase {
    public function testGetUndefinedAttribute () {
        $input = new \Gajus\Dora\Input('test');

        $this->assertNull($input->getAttribute('data-foo'));
    }

    public function testGetUndefinedIdAttribute () {
        $input = new \Gajus\Dora\Input('test');

        $this->assertNotNull($input->getAttribute('id'));
    }

    public function testGetDefinedIdAttribute () {
        $input = new \Gajus\Dora\Input('test', ['id' => 'test']);

        $this->assertSame('test', $input->getAttribute('id'));
    }

    /**
     * @expectedException Gajus\Dora\Exception\LogicException
     */
    public function testGetUndefinedIdAttributeAfterStringification () {
        $input = new \Gajus\Dora\Input('test');

        (string) $input;

        $input->getAttribute('id');
    }

    public function testSetInputAttribute () {
        $input = new \Gajus\Dora\Input('test', ['data-foo' => 'bar']);

        $this->assertSame('bar', $input->getAttribute('data-foo'));
    }

    /**
     * @expectedException Gajus\Dora\Exception\InvalidArgumentException
     */
    public function testSetNameAttribute () {
        $input = new \Gajus\Dora\Input('test', ['name' => 'test']);
    }

    /**
     * @expectedException Gajus\Dora\Exception\InvalidArgumentException
     */
    public function testSetAttributeValueNotString () {
        $input = new \Gajus\Dora\Input('test', ['test' => ['?']]);
    }
}