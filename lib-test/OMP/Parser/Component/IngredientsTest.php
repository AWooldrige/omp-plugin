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
                                             $expectedPostConsumed) {
        //Check that the data parsed is correct
        $this->assertEquals($this->component->parse($original),
                            $expectedData);

        //Check that the postConsumedText is correct
        $this->assertEquals($this->component->getPostConsumedText(),
                            $expectedPostConsumedText);
    }
    public function dataProvider_parse_valid_content() {

        $t1 = array();

        $t1[] = <<<RECIPE
=== Ingredients ===
Ingr 1 - 15 cups
Ingr 2 - 5 g - evenly sliced

=== Ingredients for Component ===
Test Ingredient
Ingredient One - 5.2 kg (or 1 cup)
Ingredient Two - 678 ml - or substitue for fresh
RECIPE;


        $t1[] = array(
                    'default' => array(
                        array(
                            'name' => 'Ingr 1',
                            'quantity' => '15 cups',
                            'directive'=> null
                        ),
                        array(
                            'name' => 'Ingr 2',
                            'quantity' => '5 g',
                            'directive'=> 'evenly sliced'
                        ),
                    ),
                    'Component' => array(
                        array(
                            'name' => 'Test Ingredient',
                            'quantity' => null,
                            'directive'=> null
                        ),
                        array(
                            'name' => 'Ingredient One',
                            'quantity' => '5.2 kg (or 1 cup)',
                            'directive'=> null
                        ),
                        array(
                            'name' => 'Ingredient Two',
                            'quantity' => '678 ml',
                            'directive'=> 'or substitute for fresh'
                        ),
                    ),
                    true
                );

            $t1[] = "";

            //Two Ingredients sections, with more text

            //Test using full text from full-recipe.txt

            //Two section headers with the same name

            //Two section headers with default


        return array($t1);
    }



    /**
     * Valid case test with:
     *  - one entry for component - null
     */
    public function test_appendParsedData_with_1default() {
        //Add a default entry
        $this->component->appendParsedData(null, array(array()));


        //Expected parsedData array
        $expected = array(
            '_' => array(array())
        );

        $this->assertEquals($this->component->getParsedData(), $expected);
    }
    /**
     * Valid case test with:
     *  - one entry for component - null
     *  - one entry for component - dish1
     */
    public function test_appendParsedData_with_1default_and_1custom() {
        //Add a default entry
        $this->component->appendParsedData(null, array(array()));

        //Add a custom entry
        $this->component->appendParsedData('dish1', array(array()));


        //Expected parsedData array
        $expected = array(
            '_' => array(array()),
            'dish1' => array(array())
        );
        $this->assertEquals($this->component->getParsedData(), $expected);
    }
    /**
     * Valid case test with:
     *  - one entry for component - null
     *  - one entry for component - dish1
     *  - one entry for component - dish2
     */
    public function test_appendParsedData_with_1default_and_2custom() {
        //Add a default entry
        $this->component->appendParsedData(null, array(array()));

        //Add a custom entry
        $this->component->appendParsedData('dish1', array(array()));

        //Add a custom entry
        $this->component->appendParsedData('dish2', array(array()));

        //Expected parsedData array
        $expected = array(
            '_' => array(array()),
            'dish1' => array(array()),
            'dish2' => array(array())
        );
        $this->assertEquals($this->component->getParsedData(), $expected);
    }
    /**
     * Invalid case test with:
     *  - two entries for component - null
     *  - one entry for component - dish1
     */
    public function test_appendParsedData_with_2default() {

        $this->setexpectedexception('InvalidArgumentException');

        //Add 2 default entries
        $this->component->appendParsedData(null, array(array()));
        $this->component->appendParsedData(null, array(array()));
    }
    /**
     * Invalid case test with:
     *  - one entry for component - null
     *  - two entries for component - dish1
     */
    public function test_appendParsedData_with_1default_2custom() {

        $this->setexpectedexception('InvalidArgumentException');

        //Add 1 default entriy
        $this->component->appendParsedData(null, array(array()));

        //Add two custom entries
        $this->component->appendParsedData('dish1', array(array()));
        $this->component->appendParsedData('dish1', array(array()));
    }
}
