<?php
require_once('lib/ComponentParser/Abstract.php');

class ComponentParser_AbstractTest extends PHPUnit_Framework_TestCase {

    protected $stub;

    /**
     * Need to create a stub, as ComponentParser_Abstract is an abstract class.
     */
    public function setUp() {
        $this->stub = $this->getMockForAbstractClass('ComponentParser_Abstract');
    }

    public function test_dummyTest() {
        $this->assertTrue(true);
    }
}
