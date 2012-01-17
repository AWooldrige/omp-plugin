<?php
require_once('lib/Utilities.php');

class UtilitiesTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider dataProvider_splitOnNewlines
     */
    public function test_splitOnNewlines($raw, $expected, $shouldEqual) {
        if($shouldEqual) {
            $this->assertEquals(Utilities::splitOnNewlines($raw), $expected);
        }
        else {
            $this->assertNotEquals(Utilities::splitOnNewlines($raw), $expected);
        }
    }


    public function dataProvider_splitOnNewlines() {
        return array(
            //No newlines
            array("A lovely document with lots of character and class",
                  array("A lovely document with lots of character and class"),
                  true),

            //2 newlines
            array("A lovely document with ".PHP_EOL.
                  "lots of character and class",
                  array("A lovely document with ",
                        "lots of character and class"),
                  true),

            //3 newlines
            array("A lovely document with ".PHP_EOL."lots of character ".
                  PHP_EOL."and class",
                  array("A lovely document with ",
                        "lots of character ",
                        "and class"),
                  true)
        );
    }


    /**
     * @dataProvider dataProvider_unifyNewlines
     */
    public function test_unifyNewlines($raw, $expected, $shouldEqual) {
        if($shouldEqual) {
            $this->assertEquals(Utilities::unifyNewlines($raw), $expected);
        }
        else {
            $this->assertNotEquals(Utilities::unifyNewlines($raw), $expected);
        }
    }

    public function dataProvider_unifyNewlines() {
        return array(
            //Mixed Data
            array("A \n wonderful line \n break.",
                  "A " . PHP_EOL . " wonderful line " . PHP_EOL . " break.",
                  true),
            array("A \r\n wonderful line \r\n break.",
                  "A " . PHP_EOL . " wonderful line " . PHP_EOL . " break.",
                  true),
            array("A \r wonderful line \r break.",
                  "A " . PHP_EOL . " wonderful line " . PHP_EOL . " break.",
                  true),
            array("A \r wonderful \n line \r\n break.",
                  "A " . PHP_EOL . " wonderful " . PHP_EOL . " line " .
                  PHP_EOL . " break.",
                  true)
        );
    }



    /**
     * @dataProvider dataProvider_splitOnParagraphs
     */
    public function test_splitOnParagraphs($raw, $expected, $shouldEqual) {

        if($shouldEqual) {
            $this->assertEquals(Utilities::splitOnParagraphs($raw), $expected);
        }
        else {
            $this->assertNotEquals(Utilities::splitOnParagraphs($raw), $expected);
        }
    }

    public function dataProvider_splitOnParagraphs() {
        return array(
            //Mixed Data
            array("A lovely".PHP_EOL.PHP_EOL."=== document === with".PHP_EOL.
                  ' '.PHP_EOL."lots of".PHP_EOL.PHP_EOL.PHP_EOL."character".
                  PHP_EOL." - and class",
                  array('A lovely',
                        '=== document === with',
                        'lots of',
                        "character".PHP_EOL." - and class"),
                  true),

            //Test that a test will fail, using a bad expected
            array("A lovely document with".PHP_EOL.' '.PHP_EOL.
                  "lots of character and class",
                  array("A lovely document with",
                        " ",
                        "lots of character and class"),
                 false),

            //Normal linebreaks included
            array("A lovely".PHP_EOL."document with".PHP_EOL.PHP_EOL.
                  "lots of character".PHP_EOL."and class",
                  array("A lovely".PHP_EOL."document with",
                        "lots of character".PHP_EOL."and class"),
                  true),

            //With a line only including whitespace
            array("A lovely document with".PHP_EOL.' '.PHP_EOL.
                  "lots of character and class",
                  array("A lovely document with",
                        "lots of character and class"),
                  true),

            //Lotes of linebreaks
            array("A lovely document with".PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.
                  "lots of character and class",
                  array("A lovely document with",
                        "lots of character and class"),
                  true),

            //Simple paragraph
            array("A lovely document with".PHP_EOL.PHP_EOL.
                  "lots of character and class",
                  array("A lovely document with",
                        "lots of character and class"),
                  true),

            //No linebreaks at all
            array("A lovely document with lots of character and class",
                  array("A lovely document with lots of character and class"),
                  true)
        );
    }
}