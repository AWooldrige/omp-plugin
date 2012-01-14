<?php
require_once('lib/ComponentParserAbstract.php');

class ComponentParserAbstractTest extends PHPUnit_Framework_TestCase {

    protected $stub;

    /**
     * Need to create a stub, as ComponentParserAbstract is an abstract class.
     */
    public function setUp() {
        $this->stub = $this->getMockForAbstractClass('ComponentParserAbstract');
    }

    public function test_dummyTest() {
        $this->assertTrue(true);
    }
}
