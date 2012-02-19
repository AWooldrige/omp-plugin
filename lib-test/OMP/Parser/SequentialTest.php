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


    /**
     * KITCHEN SINK TEST
     */
    public function test_parse_with_all() {
        $rawText = <<<RECIPE

  This is more text over a couple of lines   


This is also text at the start but shouldn't be included in the summary.

=== Ingredients ===
Test Ingredient - 2 tsp
Another - 10 g (1 tsp) - Sifted, gently
Clove

=== Ingredients for Component ===
Test Ingredient - 1 whole - sliced

=== Ingredients for Other Component ===
Test Ingredient - 1 whole - sliced
Test Ingredient - 2 whole - sliced

= equals sign in it, and at the end =
Perhaps some random text with an ^

=== Method ===
 - Test method
 - Test another method
    - Submethod
      over
      multiple lines

=== Tips ===
 - This is a tip
    - So is this
 - This is too

Some random text, perhaps
describing what it was like
to make it?
RECIPE;

        $activeComponents = array('Ingredients', 'Method', 'Tips', 'Text');

        $expectedPostConsumption = null;

        $expectedData = array(
            'Ingredients' => array(
                '_' => array(
                    array(
                        'name'      => 'Test Ingredient',
                        'quantity'  => '2 tsp',
                        'directive' => null
                    ),
                    array(
                        'name'      => 'Another',
                        'quantity'  => '10 g (1 tsp)',
                        'directive' => 'Sifted, gently'
                    ),
                    array(
                        'name'      => 'Clove',
                        'quantity'  => null,
                        'directive' => null
                    )
                ),
                'Component' => array(
                    array(
                        'name'      => 'Test Ingredient',
                        'quantity'  => '1 whole',
                        'directive' => 'sliced'
                    )
                ),
                'Other Component' => array(
                    array(
                        'name'      => 'Test Ingredient',
                        'quantity'  => '1 whole',
                        'directive' => 'sliced'
                    ),
                    array(
                        'name'      => 'Test Ingredient',
                        'quantity'  => '2 whole',
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
                    'subitems' => array(
                        array(
                            'item' => 'Submethod over multiple lines',
                            'subitems' => null
                        )
                    )
                )
            ),
            'Tips' => array(
                array(
                    'item' => 'This is a tip',
                    'subitems' => array(
                        array(
                            'item' => 'So is this',
                            'subitems' => null
                        )
                    )
                ),
                array(
                    'item' => 'This is too',
                    'subitems' => null
                )
            ),
            'Text' => array(
                'summary' => 'This is more text over a couple of lines',
                'other' => 'This is also text at the start but shouldn\'t be '.
                           'included in the summary.'.
                           PHP_EOL.PHP_EOL.
                           '= equals sign in it, and at the end = Perhaps '.
                           'some random text with an ^'.
                           PHP_EOL.PHP_EOL.
                           'Some random text, perhaps describing what it was '.
                           'like to make it?'
            )
        );

        $data = $this->parser->parse($rawText, $activeComponents);
        $postConsumed = $this->parser->getPostConsumedText();

        $this->assertEquals($expectedData, $data);
        $this->assertEquals($expectedPostConsumption, $postConsumed);
    }
}
