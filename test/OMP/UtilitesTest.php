<?php

class OMP_UtilitiesTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider dataProvider_mergeOnNewlines
     */
    public function test_mergeOnNewlines($raw, $expected, $shouldEqual) {
        if($shouldEqual) {
            $this->assertEquals(OMP_Utilities::mergeOnNewlines($raw), $expected);
        }
        else {
            $this->assertNotEquals(OMP_Utilities::mergeOnNewlines($raw), $expected);
        }
    }
    public function dataProvider_mergeOnNewlines() {
        return array(
            //No newlines
            array(array("A lovely document with lots of character and class"),
                  "A lovely document with lots of character and class",
                  true),

            //2 newlines
            array(array("A lovely document with ",
                        "lots of character and class"),
                  "A lovely document with ".PHP_EOL .
                  "lots of character and class",
                  true),

            //3 newlines
            array(array("A lovely document with ",
                        "lots of character ",
                        "and class"),
                  "A lovely document with ".PHP_EOL."lots of character ".
                  PHP_EOL."and class",
                  true),

            //Test that one fails
            array(array("A lovely document with lots of character and class"),
                  "Wrong line",
                  false)
        );
    }


    /**
     * @dataProvider dataProvider_mergeOnParagraphs
     */
    public function test_mergeOnParagraphs($raw, $expected, $shouldEqual) {
        if($shouldEqual) {
            $this->assertEquals(OMP_Utilities::mergeOnParagraphs($raw), $expected);
        }
        else {
            $this->assertNotEquals(OMP_Utilities::mergeOnParagraphs($raw), $expected);
        }
    }

    public function dataProvider_mergeOnParagraphs() {
        return array(
            //4 paragraphs, one with a section header and last with a line break
            array(array('A lovely',
                        '=== document === with',
                        'lots of',
                        "character".PHP_EOL." - and class"),
                  "A lovely".PHP_EOL.PHP_EOL.
                  "=== document === with".PHP_EOL.PHP_EOL.
                  "lots of".PHP_EOL.PHP_EOL."character".PHP_EOL." - and class",
                  true),

            //Test that a test will fail, using a bad expected.
            //In this case, only a linebreak was inserted where a paragraph
            //should have been.
            array(array("A lovely document with",
                        " ",
                        "lots of character and class"),
                  "A lovely document with".PHP_EOL.' '.PHP_EOL.
                  "lots of character and class",
                  false),

            //Only one paragraph
            array(array("Test paragraph with one line"),
                  "Test paragraph with one line",
                  true),

            //No paragraphs
            array(array(),
                  "",
                  true),

            //Multiline paragraphs
            array(array("Test line one".PHP_EOL."Test line two.".PHP_EOL."last",
                        "Anoter line one".PHP_EOL."Another line two"),
                  "Test line one".PHP_EOL."Test line two.".PHP_EOL."last".
                  PHP_EOL.PHP_EOL.
                  "Anoter line one".PHP_EOL."Another line two",
                  true)
        );
    }



    /**
     * @dataProvider dataProvider_splitOnNewlines
     */
    public function test_splitOnNewlines($raw, $expected, $shouldEqual) {
        if($shouldEqual) {
            $this->assertEquals($expected,
                OMP_Utilities::splitOnNewlines($raw));
        }
        else {
            $this->assertNotEquals($expected,
                OMP_Utilities::splitOnNewlines($raw));
        }
    }


    public function dataProvider_splitOnNewlines() {
        return array(
            //No newlines
            array(
                "A lovely document with lots of character and class",
                array(
                    "A lovely document with lots of character and class"
                ),
                true
            ),

            //2 newlines
            array(
                "A lovely document with ".PHP_EOL.
                "lots of character and class",
                array(
                    "A lovely document with ",
                    "lots of character and class"
                ),
                true
            ),

            //3 newlines
            array(
                "A lovely document with ".PHP_EOL."lots of character ".
                PHP_EOL."and class",
                array(
                    "A lovely document with ",
                    "lots of character ",
                    "and class"
                ),
                true
            ),

            //Can we cope with a trailing PHP_EOL
            array(
                "A lovely document with ".PHP_EOL."lots of character ".
                PHP_EOL."and class".PHP_EOL,
                array(
                    "A lovely document with ",
                    "lots of character ",
                    "and class"
                ),
                true
            ),

            //Make sure that a test fails
            array("A lovely document with lots of character and class",
                  array("A lovely document with lots of character and class",
                        "Wrong line"),
                  false),
        );
    }


    /**
     * @dataProvider dataProvider_unifyNewlines
     */
    public function test_unifyNewlines($raw, $expected, $shouldEqual) {
        if($shouldEqual) {
            $this->assertEquals(OMP_Utilities::unifyNewlines($raw), $expected);
        }
        else {
            $this->assertNotEquals(OMP_Utilities::unifyNewlines($raw), $expected);
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
            $this->assertEquals(OMP_Utilities::splitOnParagraphs($raw), $expected);
        }
        else {
            $this->assertNotEquals(OMP_Utilities::splitOnParagraphs($raw), $expected);
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



    /**
     * @dataProvider dataProvider_mergeManualLinebreaks
     */
    public function test_mergeManualLinebreaks($raw, $expected, $shouldEqual) {
        if($shouldEqual) {
            $this->assertEquals($expected,
                OMP_Utilities::mergeManualLinebreaks($raw));
        }
        else {
            $this->assertNotEquals($expected,
                OMP_Utilities::mergeManualLinebreaks($raw));
        }
    }
    public function dataProvider_mergeManualLinebreaks() {
        return array(
            array(
                "A paragraph with no line breaks.",
                "A paragraph with no line breaks.",
                true
            ),
            array(
                'A paragraph with' . PHP_EOL . '1 linebreak',
                'A paragraph with 1 linebreak',
                true
            ),
            array(
                'A paragraph with    ' . PHP_EOL . '   1 break and trailing',
                'A paragraph with 1 break and trailing',
                true
            ),
            array(
                'Plenty of'.PHP_EOL.'linebreaks'.PHP_EOL.'within'.PHP_EOL,
                'Plenty of linebreaks within',
                true
            ),
            array(
                'Plenty of'.PHP_EOL.'linebreaks'.PHP_EOL.'within.'.
                PHP_EOL.PHP_EOL.
                'Multiple paragraphs also'.PHP_EOL.'with linebreaks'.
                PHP_EOL.PHP_EOL.PHP_EOL.
                'Trailing paragraph with no linebreaks',

                'Plenty of linebreaks within.'.
                PHP_EOL.PHP_EOL.
                'Multiple paragraphs also with linebreaks'.
                PHP_EOL.PHP_EOL.
                'Trailing paragraph with no linebreaks',
                true
            )
        );
    }


    /**
     * @dataProvider dataProvider_isPhpLabelValid
     */
    public function test_isPhpLabelValid($raw, $isValid) {
        if($isValid) {
            $this->assertTrue(OMP_Utilities::isPhpLabelValid($raw));
        }
        else {
            $this->assertFalse(OMP_Utilities::isPhpLabelValid($raw));
        }
    }
    public function dataProvider_isPhpLabelValid() {
        return array(
            array('testTest', true),
            array('b', true),
            array('_test', true),
            array('TestCase', true),
            array('test_class5', true),

            array('4er', false),
            array('test label', false),
            array(' test_label', false),
            array('%test_label', false),
            array('.test_label', false),
        );
    }


    /**
     * @dataProvider dataProvider_convertHumanDurationToIso8601
     */
    public function test_convertHumanDurationToIso8601($human, $iso) {
        $this->assertEquals(
            $iso,
            OMP_Utilities::convertHumanDurationToIso8601($human)
        );
    }
    public function dataProvider_convertHumanDurationToIso8601() {
        return array(
            array('5 days', 'P5D'),
            array('9 years 5 days', 'P9Y5D'),
            array('6 days, 80 seconds', 'P6DT80S'),
            array('2min', 'PT2M'),
            array('2 min', 'PT2M'),
            array('2 mins', 'PT2M'),
            array('4 hours, 20 mins', 'PT4H20M'),
            array('9years2months5days60hours40minutes1second', 'P9Y2M5DT60H40M1S')
        );
    }
}
