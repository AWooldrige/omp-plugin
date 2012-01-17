<?php

class OMP_Parser_Component_AbstractTest extends PHPUnit_Framework_TestCase {

    protected $stub;

    /**
     * Need to create a stub, as ComponentParser_Abstract is an abstract class.
     */
    public function setUp() {
        $this->stub = $this->getMockForAbstractClass('OMP_Parser_Component_Abstract');
    }

    /**
     * @dataProvider dataProvider_parseSectionHeader_valid
     */
    public function test_parseSectionHeader_with_valid_data($original, $expected) {
        $cooked = OMP_Parser_Component_Abstract::parseSectionHeader($original, false);
        $this->assertEquals($cooked, $expected);
    }
    public function dataProvider_parseSectionHeader_valid() {
        return array(
            array('=== Ingredients for Test Dish',
                array('type' => 'Ingredients',
                      'for' => 'Test Dish')),
            array(' ===   Ingredients   === ',
                array('type' => 'Ingredients',
                      'for' => null)),
            array('=== Ingredients ===',
                array('type' => 'Ingredients',
                      'for' => null)),
            array(' ===  Ingredients    for Test Dish   === ',
                array('type' => 'Ingredients',
                      'for' => 'Test Dish')),
            array('=== Ingredients for Test Dish ===',
                array('type' => 'Ingredients',
                      'for' => 'Test Dish'))
        );
    }


    /**
     * @dataProvider dataProvider_parseSectionHeader_invalid
     */
    public function test_parseSectionHeader_with_invalid_data($original, $exception) {
        $this->setexpectedexception($exception);
        $cooked = OMP_Parser_Component_Abstract::parseSectionHeader($original, false);
    }
    public function dataProvider_parseSectionHeader_invalid() {
        return array(
            array('Not a fantastic thing.',
                  'InvalidArgumentException'),
            array('Not a fantastic thing ===',
                  'InvalidArgumentException'),
            array('Ingredients for Test Dish ===',
                  'InvalidArgumentException'),
            array('!*) Ingredients for Test Dish ===',
                  'InvalidArgumentException'),
            array('=== Ingredients Test Dish ===',
                  'InvalidArgumentException')
        );
    }


    public function test_getComponentName() {
        $this->setExpectedException('DomainException');
        $this->stub->getComponentName();
    }
}
