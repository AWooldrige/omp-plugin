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
     * Test the rawText getters and setters
     */
    public function test_rawText_getter_and_setter() {
        $rawText = <<<TEXT
This is a little test text over
        multiple lines, and with horrible indents
TEXT;

        $this->stub->setRawText($rawText);
        $this->assertEquals($rawText,
                            $this->stub->getRawText());
    }


    /**
     * Test the itemSpecifier getters and setters
     */
    public function test_itemSpecifier_getter_and_setter() {
        $itemSpecifier = '-';

        $this->stub->setItemSpecifier($itemSpecifier);
        $this->assertEquals($itemSpecifier,
                            $this->stub->getItemSpecifier());
    }

    /**
     * Test the constructor setters
     */
    public function test_constructor() {
        $throwaway = new OMP_Parser_Generic_List('Some text', '-');

        $this->assertNotNull($throwaway->getRawText(),
                             'Constructor did not set rawText correctly');
        $this->assertNotNull($throwaway->getItemSpecifier(),
                             'Constructor did not set itemSpecifier correctly');
    }
}
