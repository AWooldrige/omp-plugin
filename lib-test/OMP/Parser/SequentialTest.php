<?php

class OMP_Parser_SequencialTest extends PHPUnit_Framework_TestCase {
    protected $parser;

    public function setUp() {
        $this->parser = new OMP_Parser_Sequential();
    }

    public function test_parse_with_only_method() {
        $rawText = <<<RECIPE
This is more text

=== Method ===
 - Test method

=== Tips ===
 - This is a tip

Some random text
RECIPE;

        $activeComponents = array('Ingredients', 'Method');

        $expectedPostConsumption = <<<POSTCONSUMPTION
This is more text

=== Tips ===
 - This is a tip

Some random text
POSTCONSUMPTION;

        $expectedData = array(
            'Ingredients' => null,
            'Method' => array(
                array(
                    'item' => 'Test method',
                    'subitems' => null
                )
            )
        );


        $data = $this->parser->parse($rawText, $activeComponents);
        $postConsumed = $this->parser->getPostConsumedText();

        $this->assertEquals($expectedData, $data);
        $this->assertEquals($expectedPostConsumption, $postConsumed);
    }


    public function test_parse_with_two_ingredient_sections_and_method() {
        $rawText = <<<RECIPE
This is more text

=== Ingredients ===
Test Ingredient - 2 tsp

=== Ingredients for Component ===
Test Ingredient - 1 whole - sliced

=== Method ===
 - Test method
 - Test another method

=== Tips ===
 - This is a tip

Some random text
RECIPE;

        $activeComponents = array('Ingredients', 'Method');

        $expectedPostConsumption = <<<POSTCONSUMPTION
This is more text

=== Tips ===
 - This is a tip

Some random text
POSTCONSUMPTION;

        $expectedData = array(
            'Ingredients' => array(
                '_' => array(
                    array(
                        'name'      => 'Test Ingredient',
                        'quantity'  => '2 tsp',
                        'directive' => null
                    )
                ),
                'Component' => array(
                    array(
                        'name'      => 'Test Ingredient',
                        'quantity'  => '1 whole',
                        'directive' => 'sliced'
                    )
                )
            ),
            'Method' => array(
                array(
                    'item' => 'Test method',
                    'subitems' => null
                ),
                array(
                    'item' => 'Test another method',
                    'subitems' => null
                )
            )
        );


        $data = $this->parser->parse($rawText, $activeComponents);
        $postConsumed = $this->parser->getPostConsumedText();

        $this->assertEquals($expectedData, $data);
        $this->assertEquals($expectedPostConsumption, $postConsumed);
    }
}
