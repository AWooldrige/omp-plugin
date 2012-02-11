<?php

class OMP_Parser_SequencialTest extends PHPUnit_Framework_TestCase {
    protected $parser;

    public function setUp() {
        $this->parser = new OMP_Parser_Sequential();
    }

    public function test_parse() {
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
}
