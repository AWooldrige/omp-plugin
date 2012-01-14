<?php
require_once('lib/Parser/Abstract.php');

class Parser_AbstractTest extends PHPUnit_Framework_TestCase {

    protected $stub;

    public function setUp() {
        $this->stub = $this->getMockForAbstractClass('Parser_Abstract');
    }


    public function test_splitNewLines_with_no_newlines() {
        $original = "A lovely document with lots of character and class";
        $expected = array(
            "A lovely document with lots of character and class"
        );
        $this->assertEquals($this->stub->splitNewLines($original), $expected);
    }
    public function test_splitNewLines_with_2_lines() {
        $original = "A lovely document with ".PHP_EOL."lots of character and class";
        $expected = array(
            "A lovely document with ",
            "lots of character and class"
        );
        $this->assertEquals($this->stub->splitNewLines($original), $expected);
    }
    public function test_splitNewLines_with_3_lines() {
        $original = "A lovely document with ".PHP_EOL."lots of character ".PHP_EOL."and class";
        $expected = array(
            "A lovely document with ",
            "lots of character ",
            "and class"
        );
        $this->assertEquals($this->stub->splitNewLines($original), $expected);
    }
}
