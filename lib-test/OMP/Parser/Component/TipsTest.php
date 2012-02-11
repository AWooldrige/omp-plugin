<?php

class OMP_Parser_Component_TipsTest extends PHPUnit_Framework_TestCase {

    protected $component;

    public function setUp() {
        $this->component = new OMP_Parser_Component_Tips();
    }

    /**
     * Test the general use case
     */
    public function test_parse_general_use_case() {

        $rawText = <<<RECIPE
More text over

multiple paragraphs

=== Ingredients ===
Dummy Ingredient

=== Tips ===
 - This helps
 - So does this
    - This too

=== Method ===
- Do this
- Then this, but this
    - Subtask 1
    - Subtask 2
RECIPE;

        $expectedData = array(
            array(
                'item' => 'This helps',
                'subitems' => null
            ),
            array(
                'item' => 'So does this',
                'subitems' => array(
                    array(
                        'item' => 'This too',
                        'subitems' => null
                    )
                )
            )
        );

        $this->component->setRawText($rawText);
        $this->component->parse();
        $this->assertEquals($expectedData, $this->component->getParsedData());
    }


    /**
     * Test that it works in the case where there is more than one
     * tips section
     */
    public function test_parse_with_extra_tips() {

        $rawText = <<<RECIPE
More text over

multiple paragraphs

=== Ingredients ===
Dummy Ingredient

=== Tips ===
 - This helps
 - So does this
    - This too

=== Tips for Test ===
- Another method item
    - And another

=== Random for Random ===
Test
RECIPE;

        $expectedData = array(
            array(
                'item' => 'This helps',
                'subitems' => null
            ),
            array(
                'item' => 'So does this',
                'subitems' => array(
                    array(
                        'item' => 'This too',
                        'subitems' => null
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
    public function test_parse_with_no_tips_section_header() {
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
