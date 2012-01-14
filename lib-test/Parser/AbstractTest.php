<?php
require_once('lib/Parser/Abstract.php');

class Parser_AbstractTest extends PHPUnit_Framework_TestCase {

    protected $stub;

    public function setUp() {
        $this->stub = $this->getMockForAbstractClass('Parser_Abstract');
    }



    public function test_splitParagraphs_with_none() {
        $original = "A lovely document with lots of character and class";
        $expected = array(
            "A lovely document with lots of character and class"
        );
        $this->assertEquals($this->stub->splitParagraphs($original), $expected);
    }
    public function test_splitParagraphs_with_simple_paragraph() {
        $original = "A lovely document with".PHP_EOL.PHP_EOL."lots of character and class";
        $expected = array(
            "A lovely document with",
            "lots of character and class"
        );
        $this->assertEquals($this->stub->splitParagraphs($original), $expected);
    }
    public function test_splitParagraphs_with_lots_of_linebreaks() {
        $original = "A lovely document with".PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL."lots of character and class";
        $expected = array(
            "A lovely document with",
            "lots of character and class"
        );
        $this->assertEquals($this->stub->splitParagraphs($original), $expected);
    }
    public function test_splitParagraphs_with_whitespace_only_in_blank_line() {
        $original = "A lovely document with".PHP_EOL.' '.PHP_EOL."lots of character and class";
        $expected = array(
            "A lovely document with",
            "lots of character and class"
        );
        $this->assertEquals($this->stub->splitParagraphs($original), $expected);
    }
    public function test_splitParagraphs_with_normal_linebreaks_included() {
        $original = "A lovely".PHP_EOL."document with".PHP_EOL.PHP_EOL."lots of character".PHP_EOL."and class";
        $expected = array(
            "A lovely".PHP_EOL."document with",
            "lots of character".PHP_EOL."and class"
        );
        $this->assertEquals($this->stub->splitParagraphs($original), $expected);
    }
    public function test_splitParagraphs_with_bad_expected() {
        $original = "A lovely document with".PHP_EOL.' '.PHP_EOL."lots of character and class";
        $notexpected = array(
            "A lovely document with",
            " ",
            "lots of character and class"
        );
        $this->assertNotEquals($this->stub->splitParagraphs($original), $notexpected);
    }
    public function test_splitParagraphs_with_mixed_data() {
        $original = "A lovely".PHP_EOL.PHP_EOL."=== document === with".PHP_EOL.' '.PHP_EOL."lots of".PHP_EOL.PHP_EOL.PHP_EOL."character".PHP_EOL." - and class";
        $expected = array(
            'A lovely',
            '=== document === with',
            'lots of',
            "character".PHP_EOL." - and class",
        );
        $this->assertEquals($this->stub->splitParagraphs($original), $expected);
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
