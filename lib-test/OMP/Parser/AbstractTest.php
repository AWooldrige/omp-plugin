<?php

class OMP_Parser_AbstractTest extends PHPUnit_Framework_TestCase {

    protected $stub;

    public function setUp() {
        $this->stub = $this->getMockForAbstractClass('OMP_Parser_Abstract');
    }


    public function test_rawText_getters_setters() {
        $exampleText = 'Testing';
        $this->stub->setRawText($exampleText);
        $this->assertEquals($this->stub->getRawText(), $exampleText);
    }

    public function test_parsedData_getters_setters() {
        $exampleText = 'Testing';
        $this->stub->setParsedData($exampleText);
        $this->assertEquals($this->stub->getParsedData(), $exampleText);
    }

    public function test_postConsumedText_getters_setters() {
        $exampleText = 'Testing';
        $this->stub->setPostConsumedText($exampleText);
        $this->assertEquals($this->stub->getPostConsumedText(), $exampleText);
    }

    public function test_activeComponents_getters_setters() {
        $exampleComponents = array('Ingredients', 'Method', 'Tips');
        $this->stub->setActiveComponents($exampleComponents);
        $this->assertEquals($this->stub->getActiveComponents(),
                            $exampleComponents);
    }
}
