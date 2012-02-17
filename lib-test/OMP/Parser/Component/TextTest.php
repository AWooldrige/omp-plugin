<?php

class OMP_Parser_Component_TextTest extends PHPUnit_Framework_TestCase {

    protected $component;

    public function setUp() {
        $this->component = new OMP_Parser_Component_Text();
    }

    /**
     * Test the general use cases
     * @dataProvider dataProvider_parse_general_use_cases
     */
    public function test_parse_general_use_cases($rawText, $expectedData,
                                                 $expectedPostConsumedText) {

        $actualData = $this->component->parse($rawText);
        $this->assertEquals($actualData, $expectedData);
        $this->assertEquals($this->component->getPostConsumedText(),
                            $expectedPostConsumedText);
    }
    public function dataProvider_parse_general_use_cases() {

        $toReturn = array();
        $t = array();


        /**
         * Only one paragraph of text, a summary line containing no line breaks
         */
        $t[0] = <<<RECIPE
This is some example more text, with only one line. Only one paragraph

=== Ingredients ===
Ingr 1 - 15 cups
Ingr 2 - 5 g - evenly sliced

=== Ingredients for Component ===
Test Ingredient
Ingredient One - 5.2 kg (or 1 cup)
Ingredient Two - 678 ml - or substitute for fresh
RECIPE;

        $t[1] = array(
            'summary' => 'This is some example more text, with only one line. Only one paragraph',
            'other' => null
        );


        $t[2] = <<<RECIPE
=== Ingredients ===
Ingr 1 - 15 cups
Ingr 2 - 5 g - evenly sliced

=== Ingredients for Component ===
Test Ingredient
Ingredient One - 5.2 kg (or 1 cup)
Ingredient Two - 678 ml - or substitute for fresh
RECIPE;

        $toReturn[] = $t;



        /**
         * Two paragraphs of text, with no line breaks. One should be a summary,
         * the other a general text paragraph.
         */
        $t[0] = <<<RECIPE
Some example more text, spanning over:

Multiple paragraphs

=== Ingredients ===
Ingr 1 - 15 cups
Ingr 2 - 5 g - evenly sliced

=== Ingredients for Component ===
Test Ingredient
Ingredient One - 5.2 kg (or 1 cup)
Ingredient Two - 678 ml - or substitute for fresh
RECIPE;

        $t[1] = array(
            'summary' => 'Some example more text, spanning over:',
            'other' => 'Multiple paragraphs'
        );


        $t[2] = <<<POSTCONSUMED
=== Ingredients ===
Ingr 1 - 15 cups
Ingr 2 - 5 g - evenly sliced

=== Ingredients for Component ===
Test Ingredient
Ingredient One - 5.2 kg (or 1 cup)
Ingredient Two - 678 ml - or substitute for fresh
POSTCONSUMED;

        $toReturn[] = $t;



        /**
         * More text with manual linebreaks. Scattering of paragraphs elsewhere
         */
        $t[0] = <<<RECIPE
Some example more text, including
manual line breaks which should be merged
into one line.

This text shouldn't be classed as more text
and again is over multiple lines.

Yet another paragraph of fun.

=== Ingredients ===
Ingr 1 - 15 cups
Ingr 2 - 5 g - evenly sliced

A paragraph inserted between two other section
headers, split over multiple lines.

=== Ingredients for Component ===
Test Ingredient
Ingredient One - 5.2 kg (or 1 cup)
Ingredient Two - 678 ml - or substitute for fresh

Another paragrph inserted here.

=== Other ===
Random text
over multiple lines

=== Random for No Reason ===
More dull text over multiple
lines
RECIPE;

        $leftover = <<<LEFTOVER
This text shouldn't be classed as more text and again is over multiple lines.

Yet another paragraph of fun.

A paragraph inserted between two other section headers, split over multiple lines.

Another paragrph inserted here.
LEFTOVER;
        $t[1] = array(
            'summary' => 'Some example more text, including manual line breaks which should be merged into one line.'
            'other' => $leftover
        );


        $t[2] = <<<POSTCONSUMED
=== Ingredients ===
Ingr 1 - 15 cups
Ingr 2 - 5 g - evenly sliced

=== Ingredients for Component ===
Test Ingredient
Ingredient One - 5.2 kg (or 1 cup)
Ingredient Two - 678 ml - or substitute for fresh

=== Other ===
Random text
over multiple lines

=== Random for No Reason ===
More dull text over multiple
lines
POSTCONSUMED;

        $toReturn[] = $t;

        return $toReturn;
    }
}
