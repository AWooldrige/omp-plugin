<?php

class OMP_Parser_AbstractTest extends PHPUnit_Framework_TestCase {

    protected $stub;

    public function setUp() {
        $this->stub = $this->getMockForAbstractClass('OMP_Parser_Abstract');
    }

    public function test_dummyTest() {
        $this->assertTrue(true);
    }
}
