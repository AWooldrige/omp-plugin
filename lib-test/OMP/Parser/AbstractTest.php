<?php
require_once('lib/Parser/Abstract.php');

class Parser_AbstractTest extends PHPUnit_Framework_TestCase {

    protected $stub;

    public function setUp() {
        $this->stub = $this->getMockForAbstractClass('Parser_Abstract');
    }

    public function test_dummyTest() {
        $this->assertTrue(true);
    }
}
