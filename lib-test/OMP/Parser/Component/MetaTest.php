<?php

class OMP_Parser_Component_MetaTest extends PHPUnit_Framework_TestCase {

    protected $component;

    public function setUp() {
        $this->component = new OMP_Parser_Component_Meta();
    }

    /**
     * @dataProvider dataProvider_meta_valid_examples
     */
    public function test_meta_valid_examples($rawText, $expectedData,
                                             $expectedPostConsumedText) {
        $actualData = $this->component->parse($rawText);
        $this->assertEquals($expectedData, $actualData);
        $this->assertEquals($expectedPostConsumedText,
                            $this->component->getPostConsumedText());
    }
    public function dataProvider_meta_valid_examples() {
        $returnArray = array();

        //Generic use case
        $tmp = array();
        $tmp[] = <<<META
Some more text

=== Ingredients ===
Test Ingredient - 1

Random more
text

=== Meta ===
Active Time - 20 m
Inactive Time - 0

=== Tips ===
 - Test tip
META;
        $tmp[] = array(
            'Active Time' => '20 m',
            'Inactive Time' => '0'
        );
        $tmp[] = <<<POSTCONSUMED
Some more text

=== Ingredients ===
Test Ingredient - 1

Random more
text

=== Tips ===
 - Test tip
POSTCONSUMED;
        $returnArray[] = $tmp;

        //Test with varying case
        $tmp = array();
        $tmp[] = <<<META
Some more text

=== Ingredients ===
Test Ingredient - 1

Random more
text

=== Meta ===
acTive tiMe - 20 m
InacTivE TIme - 0

=== Tips ===
 - Test tip
META;
        $tmp[] = array(
            'Active Time' => '20 m',
            'Inactive Time' => '0'
        );
        $tmp[] = <<<POSTCONSUMED
Some more text

=== Ingredients ===
Test Ingredient - 1

Random more
text

=== Tips ===
 - Test tip
POSTCONSUMED;
        $returnArray[] = $tmp;

        //Kitchen sink test
        $tmp = array();
        $tmp[] = <<<META
Some more text

=== Ingredients ===
Test Ingredient - 1

Random more
text

=== Meta ===
Active Time - 20m
Inactive Time - 1hr 40m
Difficulty - 2
Rating - 5
Cost - £4.20

=== Tips ===
 - Test tip
META;
        $tmp[] = array(
            'Active Time' => '20m',
            'Inactive Time' => '1hr 40m',
            'Difficulty' => '2',
            'Rating' => '5',
            'Cost' => '£4.20'
        );
        $tmp[] = <<<POSTCONSUMED
Some more text

=== Ingredients ===
Test Ingredient - 1

Random more
text

=== Tips ===
 - Test tip
POSTCONSUMED;
        $returnArray[] = $tmp;

        return $returnArray;
    }

    /**
     * @dataProvider dataProvider_parse_exception_raising
    public function test_parse_exception_raising($rawText) {
    }
    public function dataProvider_parse_exception_raising() {
        return array(
            array(
            )
        );
    }
     */




    /**
     * @dataProvider dataProvider_normaliseMetaName_valid_examples
     */
    public function test_normaliseMetaName_valid_examples($rawText, $expected) {
        $actualData = $this->component->normaliseMetaName($rawText);
        $this->assertEquals($expected, $actualData);
    }
    public function dataProvider_normaliseMetaName_valid_examples() {
        return array(
            array('One', 'one'),
            array('Two Words', 'two_words'),
            array('tHree woRds TEST', 'three_words_test'),
            array('already_correct', 'already_correct'),
            array('  extra   whitespace   ', 'extra_whitespace')
        );
    }

    /**
     * @dataProvider dataProvider_normaliseMetaName_invalid_examples
     */
    public function test_normaliseMetaName_invalid_examples($rawText, $expected) {
        $actualData = $this->component->normaliseMetaName($rawText);
        $this->assertNotEquals($expected, $actualData);
    }
    public function dataProvider_normaliseMetaName_invalid_examples() {
        return array(
            array('One', 'One'),
            array('Two Words', 'two words'),
            array('tHree woRds TEST', 'Threewords_test'),
        );
    }

    /**
     * @dataProvider dataProvider_normaliseMetaName_exception_raising
     */
    public function test_normaliseMetaName_exception_raising($rawText, $exception) {
        $this->setExpectedException($exception);
        $actualData = $this->component->normaliseMetaName($rawText);
    }
    public function dataProvider_normaliseMetaName_exception_raising() {
        return array(
            array('& Test', 'InvalidArgumentException'),
            array('  " Test Other', 'InvalidArgumentException'),
        );
    }
}
