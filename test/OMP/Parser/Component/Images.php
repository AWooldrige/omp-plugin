<?php

class OMP_Parser_Component_ImagesTest extends PHPUnit_Framework_TestCase {

    protected $component;

    public function setUp() {
        $this->component = new OMP_Parser_Component_Images();
    }


    /**
     * IMAGES LINE PARSER TESTS
     */

    /**
     * @dataProvider dataProvider_parseLine_valid
     */
    public function test_parseLine_with_valid_data($original, $expected) {
        $cooked = $this->component->parseLine($original, false);
        $this->assertEquals($cooked, $expected);
    }

    /**
     * @dataProvider dataProvider_parseLine_invalid
     */
    public function test_parseLine_with_invalid_data($original, $exception) {
        $this->setexpectedexception($exception);
        $cooked = $this->component->parseLine($original, false);
    }

    public function dataProvider_parseLine_valid() {
        return array(
            array(
                '      321 - This is some test text.  ',
                array('attachment_id' => 321,
                      'description' => 'This is some test text.')
            ),
            array(
                '1 - T',
                array('attachment_id' => 1, 'description' => 'T')
            ),
            array(
                '123-Test',
                array('attachment_id' => 123, 'description' => 'Test')
            ),
            array(
                '321',
                array('attachment_id' => 321, 'description' => null)
            )
        );
    }
    public function dataProvider_parseLine_invalid() {
        return array(
            array('', 'InvalidArgumentException'),
            array('31a2 - Test', 'InvalidArgumentException'),
            array('a12 - Test', 'InvalidArgumentException'),
            array('12a - Test', 'InvalidArgumentException'),
            array('a - Test', 'InvalidArgumentException'),
            array('3a1 - Test', 'InvalidArgumentException'),
            array('321 -', 'InvalidArgumentException')
        );
    }


    /**
     * Testing the parse function. Quite a high level test, and it does test
     * some already covered lower level components. Not sure of the best way
     * to get round that.
     *
     * No content here should generate an exception, those need to go in the
     * 'invalid_content' test for parse.
     *
     * @dataProvider dataProvider_parse_valid_content
     */
    public function test_parse_valid_content($original, $expectedData,
                                             $expectedPostConsumedText) {

        //Check that the data parsed is correct
        $this->assertEquals($expectedData, $this->component->parse($original));

        //Check that the postConsumedText is correct
        $this->assertEquals($this->component->getPostConsumedText(),
                            $expectedPostConsumedText);
    }
    public function dataProvider_parse_valid_content() {

        $toReturn = array();
        $t = array();


        $t[0] = <<<RECIPE
=== Images ===
431 - Some great image
12 - Other image
1
RECIPE;

        $t[1] = array(
            array('attachment_id' => '431', 'description' => 'Some great image'),
            array('attachment_id' => '12', 'description' => 'Other image'),
            array('attachment_id' => '1', 'description' => Null)
        );

        $t[2] = "";

        $toReturn[] = $t;

            //Two Images sections, with more text
        $t[0] = <<<RECIPE
Some example more text

=== Images ===
431-Some great image

=== Ingredients for Component ===
Test Ingredient
Ingredient One - 5.2 kg (or 1 cup)
Ingredient Two - 678 ml - or substitute for fresh
RECIPE;

        $t[1] = array(
            array('attachment_id' => '431', 'description' => 'Some great image')
        );

        $t[2] = <<<POSTCONSUMED
Some example more text

=== Ingredients for Component ===
Test Ingredient
Ingredient One - 5.2 kg (or 1 cup)
Ingredient Two - 678 ml - or substitute for fresh
POSTCONSUMED;

        $toReturn[] = $t;

        return $toReturn;
    }
}
