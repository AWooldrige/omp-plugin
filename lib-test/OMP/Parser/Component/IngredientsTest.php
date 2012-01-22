<?php

class OMP_Parser_Component_IngredientsTest extends PHPUnit_Framework_TestCase {

    protected $component;

    public function setUp() {
        $this->component = new OMP_Parser_Component_Ingredients();
    }


    /**
     * INGREDIENT LINE PARSER TESTS
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
                '     Test Ingredient  '.OMP_Parser_Component_Ingredients::SEP.'100g  '.OMP_Parser_Component_Ingredients::SEP.'  thinly sliced',
                array('name' => 'Test Ingredient',
                      'quantity' => '100g',
                      'directive' => 'thinly sliced'),
            ),
            array(
                'Test Ingredient - 100g - thinly sliced',
                array('name' => 'Test Ingredient',
                          'quantity' => '100g',
                          'directive' => 'thinly sliced')
            ),
            array(
                'Test Ingredient'.OMP_Parser_Component_Ingredients::SEP.'100g',
                array('name' => 'Test Ingredient',
                          'quantity' => '100g',
                          'directive' => null)
            ),
            array(
                'Test Ingredient',
                array('name' => 'Test Ingredient',
                          'quantity' => null,
                          'directive' => null),
            ),
            array(
                '     Test Ingredient  '.OMP_Parser_Component_Ingredients::SEP.'100g  '.OMP_Parser_Component_Ingredients::SEP.'  thinly sliced',
                array('name' => 'Test Ingredient',
                          'quantity' => '100g',
                          'directive' => 'thinly sliced')
            )
        );
    }
    public function dataProvider_parseLine_invalid() {
        return array(
            array('',
                  'InvalidArgumentException'),
            array('Test Ingredient'.OMP_Parser_Component_Ingredients::SEP.'',
                  'InvalidArgumentException')
        );
    }


    public function test_parse() {
        //One plain ingredients section, no more text

        //Two Ingredients sections, with more text

        //Test using full text from full-recipe.txt

    }

}
