<?php

class OMP_Parser_Generic_ListTest extends PHPUnit_Framework_TestCase {

    protected $stub;


    /**
     * A new stub for each test
     */
    public function setUp() {
        $this->stub = new OMP_Parser_Generic_List();
    }


    /**
     * Text the rawText getters and setters
     */
    public function test_rawText_getter_and_setter() {
        $exampleText = <<<TEXT
This is a little test text over
        multiple lines, and with horrible indents
TEXT;

        $this->stub->setRawText($exampleText);
        $this->assertEquals($exampleText,
                            $this->stub->getRawText());
    }
}
