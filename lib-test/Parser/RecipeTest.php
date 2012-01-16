<?php
require_once('lib/Parser/Recipe.php');

/**
 * @outputBuffering disabled
 */
class Parser_RecipeTest extends PHPUnit_Framework_TestCase {
    protected $parser;

    public function setUp() {
        $this->parser = new Parser_Recipe();
    }

/*
3 === Ingredients ===
  4 Tortilla Wraps - 6
  5 Red Pepper - 1 cup - sliced

  7 === Ingredients for Excellent Side Dish ===
  8 Chicken Breast - 2 - sliced
  9 White Onion - 1 large - thinly sliced
 10 Liquid smoke - 1 tsp
 11 Cornflour
*/
    /**
     * What happens if the contents of an
     *
     * === Ingredients ===
     *
     * header is given without any contents below it.
     */
/*
    public function test_cIngredientList_empty_contents() {
        $original = array(
"=== Ingredients ===
Tortilla Wraps - 6
Red Pepper - 1 cup - sliced",
"=== Ingredients for Excellent Side Dish ===
Chicken Breast - 2 - sliced
White Onion - 1 large - thinly sliced
Liquid smoke - 1 tsp
Cornflour"
);
        $expected = array(
            'main' => array(
                array(
                    'name'      => 'Tortilla Wraps',
                    'quantity'  => '6',
                    'directive' => null),
                array(
                    'name'      => 'Red Pepper',
                    'quantity'  => '1 cup',
                    'directive' => 'sliced')
            ),
            'Excellent Side Dish' => array(
                array(
                    'name'      => 'Chicken Breast',
                    'quantity'  => '2',
                    'directive' => 'sliced'),
                array(
                    'name'      => 'White Onion',
                    'quantity'  => '1 large',
                    'directive' => 'thinly sliced'),
                array(
                    'name'      => 'Liquid Smoke',
                    'quantity'  => '1 tsp',
                    'directive' => null),
                array(
                    'name'      => 'Cornflour',
                    'quantity'  => null,
                    'directive' => null)
            )
        );

        $cooked = $this->parser->cIngredientList($original);
        $this->assertEquals($cooked, $expected);
    }
*/


    /**
     * @dataProvider dataProvider_pIngredientLine_valid
     */
    public function test_pIngredientLine_with_valid_data($original, $expected) {
        $cooked = $this->parser->pIngredientLine($original, false);
        $this->assertEquals($cooked, $expected);
    }

    /**
     * @dataProvider dataProvider_pIngredientLine_invalid
     */
    public function test_pIngredientLine_with_invalid_data($original, $exception) {
        $this->setexpectedexception($exception);
        $cooked = $this->parser->pIngredientLine($original, false);
    }

    public function dataProvider_pIngredientLine_valid() {
        return array(
            array(
                '     Test Ingredient  '.Parser_Recipe::SEP.'100g  '.Parser_Recipe::SEP.'  thinly sliced',
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
                'Test Ingredient'.Parser_Recipe::SEP.'100g',
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
                '     Test Ingredient  '.Parser_Recipe::SEP.'100g  '.Parser_Recipe::SEP.'  thinly sliced',
                array('name' => 'Test Ingredient',
                          'quantity' => '100g',
                          'directive' => 'thinly sliced')
            )
        );
    }
    public function dataProvider_pIngredientLine_invalid() {
        return array(
            array('',
                  'InvalidArgumentException'),
            array('Test Ingredient'.Parser_Recipe::SEP.'',
                  'InvalidArgumentException')
        );
    }



    public function test_pIngredientParagraph_with_valid() {
        $original = '=== Ingredients for Test Dish ===' . PHP_EOL .
                    'Test Ingredient - 2 cups - thinly sliced' . PHP_EOL .
                    'Test - 4';
        $expected = array(
            'for' => 'Test Dish',
            'items' => array(
                array(
                    'name' => 'Test Ingredient',
                    'quantity' => '2 cups',
                    'directive' => 'thinly sliced'),
                array(
                    'name' => 'Test',
                    'quantity' => '4',
                    'directive' => null)
            )
        );

        $cooked = $this->parser->pIngredientsParagraph($original);
        $this->assertEquals($cooked, $expected);
    }

    public function test_big_overall_test() {
        $recipe = 'This is a test of some more text.' . PHP_EOL .
                  '' . PHP_EOL .
                  'More text over multiple paragraphs' . PHP_EOL .
                  '' . PHP_EOL .
                  '=== Ingredients for Test Dish ===' . PHP_EOL .
                  'Test Ingredient - 2 cups - thinly sliced' . PHP_EOL .
                  'Test - 4' . PHP_EOL .
                  'Another Test - 10g (2 cups) - extra needed' . PHP_EOL;

        $expected = array();
    }
}
