<?php

class OMP_Parser_Component_MethodTest extends PHPUnit_Framework_TestCase {

    protected $component;

    public function setUp() {
        $this->component = new OMP_Parser_Component_Method();
    }

    public function test_parse_general_case() {

        /**
         * Test that it works in a general case
         */
        $rawText = <<<RECIPE
More text over

multiple paragraphs

=== Ingredients ===
Dummy Ingredient

=== Method ===
- Do this
- Then this, but this
    - Subtask 1
    - Subtask 2

=== Random for Random ===
Test
RECIPE;

        $expectedData = array(
            array(
                'item' => 'Do this',
                'subitems' => null
            ),
            array(
                'item' => 'Then this, but this',
                'subitems' => array(
                    array(
                        'item' => 'Subtask 1',
                        'subitems' => null
                    ),
                    array(
                        'item' => 'Subtask 2',
                        'subitems'=> null
                    )
                )
            )
        );

        $this->assertEquals($expectedData, $this->component->parse($rawText));
    }

    public function test_parse_with_extra_method() {

        /**
         * Test that it works in a general case
         */
        $rawText = <<<RECIPE
More text over

multiple paragraphs

=== Ingredients ===
Dummy Ingredient

=== Method ===
- Do this
- Then this, but this
- Subtask 1
- Subtask 2

=== Method for Test ===
- Another method item
    - And another

=== Random for Random ===
Test
RECIPE;

        $expectedData = array(
            array(
                'item' => 'Do this',
                'subitems' => null
            ),
            array(
                'item' => 'Then this, but this',
                'subitems' => array(
                    array(
                        'item' => 'Subtask 1',
                        'subitems' => null
                    ),
                    array(
                        'item' => 'Subtask 2',
                        'subitems'=> null
                    )
                )
            )
        );

        $this->setExpectedException('InvalidArgumentException');
        $this->assertEquals($expectedData, $this->component->parse($rawText));
    }

    /**
     * Make sure the world doesn't cave in if no method section is provided
     */
    public function test_parse_with_no_method_section() {
        $rawText = <<<RECIPE
More text over

multiple paragraphs

=== Ingredients ===
Dummy Ingredient


=== Random for Random ===
Test
RECIPE;

        $expectedData = null;
        $this->assertEquals($expectedData, $this->component->parse($rawText));
    }
}
